<?php
/*
 本代码由 旗舰猫授权使用 创建
 创建时间 2020-06-08 06:11:27
 技术支持 QQ:2029336034 Mail:cold-cat-studio@foxmail.com
 严禁反编译、逆向等任何形式的侵权行为，违者将追究法律责任
*/

namespace App\Console\Commands;

use App\Market;
use App\Utils\RPC;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GetMarket extends Command
{
    protected $signature = "\x67\x65\x74\x5F\x6D\x61\x72\x6B\x65\x74";
    protected $description = "\xE8\x8E\xB7\xE5\x8F\x96\xE8\xA1\x8C\xE6\x83\x85";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $N2wvPvP8E = "Accepts: application/json
" . "X-CMC_PRO_API_KEY: dd75f65a-566e-4639-9099-3d29f04f0d2c
";
        unset($N2wtI8F);
        $N2wtI8F = ["http" => ["method" => "GET", "header" => $N2wvPvP8E]];
        $opts = $N2wtI8F;
        unset($N2wtI8E);
        $context = stream_context_create($opts);
        unset($N2wtI8E);
        $file = file_get_contents('https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest?id=1,2', false, $context);
        unset($N2wtI8E);
        $coin_list = json_decode($file, true);
        DB::beginTransaction();
        try {
            $N2wbN8F = 0 == strlen(4);
            if ($N2wbN8F) goto N2weWjgx3;
            if (isset($_N2wIfQU)) goto N2weWjgx3;
            $N2w8E = !empty($coin_list['data']);
            if ($N2w8E) goto N2weWjgx3;
            goto N2wldMhx3;
            N2weWjgx3:
            goto N2wMrKh14B;
            $N2wM8G = $R4vP4 . DS;
            unset($N2wtIM8H);
            $R4vP5 = $N2wM8G;
            unset($N2wtIM8I);
            $R4vA5 = array();
            unset($N2wtIM8J);
            $R4vA5[] = $request;
            unset($N2wtIM8K);
            $R4vC3 = call_user_func_array($R4vA5, $R4vA4);
            N2wMrKh14B:
            goto N2wMrKh14D;
            unset($N2wtIM8L);
            $R4vA1 = array();
            unset($N2wtIM8M);
            $N2wtIM8M =& $dispatch;
            $R4vA1[] =& $N2wtIM8M;
            unset($N2wtIM8N);
            $R4vA2 = array();
            unset($N2wtIM8O);
            $R4vC0 = call_user_func_array($R4vA2, $R4vA1);
            N2wMrKh14D:
            foreach ($coin_list['data'] as $row) {
                unset($N2wtI8E);
                $market = Market::find($row['id']);
                if (empty($market)) goto N2weWjgx5;
                unset($N2wtIvPbN8G);
                $N2wIfQU = "";
                if (ltrim($N2wIfQU)) goto N2weWjgx5;
                $N2wbN8E = 4 === "";
                unset($N2wtIbN8F);
                $N2wIfQU = $N2wbN8E;
                if ($N2wIfQU) goto N2weWjgx5;
                goto N2wldMhx5;
                N2weWjgx5:
                goto N2wMrKh14F;
                $N2wM8H = $R4vP4 . DS;
                unset($N2wtIM8I);
                $R4vP5 = $N2wM8H;
                unset($N2wtIM8J);
                $R4vA5 = array();
                unset($N2wtIM8K);
                $R4vA5[] = $request;
                unset($N2wtIM8L);
                $R4vC3 = call_user_func_array($R4vA5, $R4vA4);
                N2wMrKh14F:
                goto N2wMrKh151;
                unset($N2wtIM8M);
                $R4vA1 = array();
                unset($N2wtIM8N);
                $N2wtIM8N =& $dispatch;
                $R4vA1[] =& $N2wtIM8N;
                unset($N2wtIM8O);
                $R4vA2 = array();
                unset($N2wtIM8P);
                $R4vC0 = call_user_func_array($R4vA2, $R4vA1);
                N2wMrKh151:
                $N2w8Q = new Market();
                unset($N2wtI8R);
                $market = $N2w8Q;
                goto N2wx4;
                N2wldMhx5:N2wx4:
                unset($N2wtI8E);
                $market->id = $row['id'];
                unset($N2wtI8E);
                $market->name = $row['name'];
                unset($N2wtI8E);
                $market->symbol = $row['symbol'];
                unset($N2wtI8E);
                $market->rank = $row['cmc_rank'];
                unset($N2wtI8E);
                $market->circulating_supply = $row['circulating_supply'];
                unset($N2wtI8E);
                $market->total_supply = $row['total_supply'];
                unset($N2wtI8E);
                $market->max_supply = $row['max_supply'];
                unset($N2wtI8E);
                $market->quotes = serialize($row['quote']);
                unset($N2wtI8E);
                $market->last_updated = $row['last_updated'];
                $market->save();
            }
            DB::commit();
            echo 111;
            $N2w8E = '请求接口成功，并更新数据库->' . date('Y-m-d H:i:s');
            unset($N2wtI8F);
            $message = $N2w8E;
            $this->info($message);
            goto N2wx2;
            N2wldMhx3:
            goto N2wMrKh153;
            $N2wM8G = $R4vP4 . DS;
            unset($N2wtIM8H);
            $R4vP5 = $N2wM8G;
            unset($N2wtIM8I);
            $R4vA5 = array();
            unset($N2wtIM8J);
            $R4vA5[] = $request;
            unset($N2wtIM8K);
            $R4vC3 = call_user_func_array($R4vA5, $R4vA4);
            N2wMrKh153:
            goto N2wMrKh155;
            unset($N2wtIM8L);
            $R4vA1 = array();
            unset($N2wtIM8M);
            $N2wtIM8M =& $dispatch;
            $R4vA1[] =& $N2wtIM8M;
            unset($N2wtIM8N);
            $R4vA2 = array();
            unset($N2wtIM8O);
            $R4vC0 = call_user_func_array($R4vA2, $R4vA1);
            N2wMrKh155:
            echo 222;
            $N2w8P = '请求数据接口失败，无数据->' . date('Y-m-d H:i:s');
            unset($N2wtI8Q);
            $message = $N2w8P;
            $this->info($message);
            N2wx2:
        } catch (\Exception $exception) {
            DB::rollback();
            echo 333;
            $N2w8E = $exception->getMessage() . '->';
            $N2w8F = $N2w8E . date('Y-m-d H:i:s');
            unset($N2wtI8G);
            $message = $N2w8F;
            $this->info($message);
        }
    }
}

?>