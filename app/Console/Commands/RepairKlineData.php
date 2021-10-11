<?php
namespace App\Console\Commands;

use App\Service\RedisService;
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

    protected static $resoucesUrl = 'https://zitzoom.com/Json/temp/Chain/Line/{{period}}/{{name}}.json';

    protected static $period = [
//        5 => "1min",
//        6 => "5min",
//        1 => "15min",
//        7 => "30min",
//        2 => "60min",
//        3 => "1hour",
//        4 => "1day",
        8 => "1week",
        9 => "1mon",
        10 => "1year",
    ];

    public function handle()
    {
        $redis = \Illuminate\Support\Facades\Redis::connection();
//        $redis = RedisService::getInstance();
        $cacheKey = 'repairKlineData';
        $currencyListString = $redis->get($cacheKey);
        if(! $currencyListString) {
            $currencyList = DB::table('currency')->get()->toArray();
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
//                    $resources = file_get_contents($url);
//                    if($resources)
//                    {
//
//                    }
//                    FormatOutput
                    echo $url . "\n";
                }
            }
        }

    }

    public function setEs($market_data)
    {
        $es_client = self::getEsearchClient();
        $type = $market_data['base-currency'] . '.' . $market_data['quote-currency'] . '.' . $market_data['period'];
        // $market_data['close']>6600&&var_dump($market_data);
        $params = [
            'index' => 'market.kline',
            'type' => 'doc',
            'id' => $type . '.' . $market_data['id'],
            'body' => $market_data,
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

