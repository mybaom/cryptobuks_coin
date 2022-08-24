<?php
namespace App\Console\Commands;

use App\Service\RedisService;
use App\Utils\FormatOutput;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RepairKlineData extends Command
{

    protected $signature = "RepairKlineData";
    protected $description = "修复k线数据";

    public $timestamps = false;

    protected static $esClient = null;

//    protected static $resoucesUrl = 'https://zitzoom.com/Json/temp/Chain/Line/{{period}}/{{name}}.json';
    protected static $resoucesUrl = 'https://api.zb.com/data/v1/kline?market={{name}}_usdt&type={{period}}';

    protected static $period = [
        5 => "1min",
        6 => "5min",
        1 => "15min",
        7 => "30min",
        2 => "60min",
        3 => "1hour",
        4 => "1day",
//        8 => "1week",
//        9 => "1mon",
//        10 => "1year",
    ];

    public function handle()
    {
        $redis = \Illuminate\Support\Facades\Redis::connection();
//        $redis = RedisService::getInstance();
        $cacheKey = 'repairKlineData';
        $currencyListString = $redis->get($cacheKey);
        if(! $currencyListString) {
            $currencyList = DB::table('currency')->where('id', '<>', 3)->get()->toArray();
            if(! $currencyList)
            {
                throw new \Exception('get currency list failed on db.');
            }
            $currencyListString = json_encode($currencyList, JSON_UNESCAPED_UNICODE);
            $setRedisValRes = $redis->set($cacheKey, $currencyListString);
            $setRedisExpRes = $redis->expire($cacheKey, 3600*24);
            if(! $setRedisValRes || !$setRedisExpRes)
            {
                throw new \Exception('set redis failed.');
            }
        }

        $currencyList = json_decode($currencyListString, true);

        if ($currencyList) {
            foreach ($currencyList as $k => $v) {
                foreach (self::$period as $n => $i) {
                    $url = str_replace('{{name}}', $v['name'], str_replace('{{period}}', $i, self::$resoucesUrl));
                    $file_headers = @get_headers($url);
                    if(!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found' ||trim($file_headers[0]) == 'HTTP/1.1 403 Forbidden') {
                        echo FormatOutput::red('资源不存在，url：' . $url, '') .PHP_EOL;
                        continue;
                    }

                    $resourcesString = file_get_contents($url);
                    if(! $resourcesString)
                    {
                        echo FormatOutput::red('获取资源失败，url：' . $url, '') .PHP_EOL;
                        continue;
                    }
                    try{
                        $resources = json_decode($resourcesString, true);
                    }catch (\Exception $e){
                        echo FormatOutput::red('获取资源数据失败，url：' . $url, '') .PHP_EOL;
                    }
                    if(empty($resources['data']))
                    {
                        echo FormatOutput::red('获取资源数据失败，url：' . $url, '') .PHP_EOL;
                        continue;
                    }
                    if(empty($resources['moneyType']))
                    {
                        echo FormatOutput::red('获取资源类型失败，url：' . $url, '') .PHP_EOL;
                        continue;
                    }
                    if(strpos($resources['moneyType'], 'USDT') === false)
                    {
                        echo FormatOutput::red('资源类型非USDT，url：' . $url, '') .PHP_EOL;
                        continue;
                    }
                    $this->executeResources($v, $resources['data'], $i);
                }
            }

            echo FormatOutput::red('end', '') .PHP_EOL;
        }
    }

    public function executeResources($currency, $resources, $period)
    {
        try {
            foreach ($resources as $k => $v)
            {
                $marketData = [
                    "id"             => $v[0],
                    "period"         => $period,
                    "base-currency"  => $currency['name'],
                    "quote-currency" => "USDT",
                    "open"           => $v[1],
                    "high"           => $v[2],
                    "low"            => $v[3],
                    "close"          => $v[4],
                    "vol"            => $v[5],
                    "amount"         => $v[5]
                ];

                $setEsResult = $this->setEs($marketData);

                if(! $setEsResult)
                {
                    echo FormatOutput::red("写入Es失败，名称：$currency[name]，类型：$period " . "时间：$v[id]") .PHP_EOL;
                }

            }
        }catch (\Exception $e){
            $message = $e->getMessage();
            echo FormatOutput::red("异常：" . $message .  "，名称：$currency[name]，类型：$period " . "，数据：" . json_encode($v, JSON_UNESCAPED_UNICODE)) .PHP_EOL;
            die;
        }
    }

    public function setEs($marketData)
    {
        $es_client = self::getEsearchClient();
        $type = $marketData['base-currency'] . '.' . $marketData['quote-currency'] . '.' . $marketData['period'];
        // $market_data['close']>6600&&var_dump($market_data);
        $params = [
            'index' => 'market.kline',
            'type' => 'doc',
            'id' => $type . '.' . $marketData['id'],
            'body' => $marketData,
        ];
        $response = $es_client->index($params);

        return $response;
    }

    /**
     * 获得一个ElasticsearchClient实例
     *
     * @return Client
     */
    public static function getEsearchClient()
    {
        if (is_null(self::$esClient)) {
            $hosts = config('elasticsearch.hosts');
            self::$esClient = ClientBuilder::create()
                ->setHosts($hosts)
                ->build();
        }
        return self::$esClient;
    }
}

