<?php
/*
 本代码由 旗舰猫授权使用 创建
 创建时间 2020-06-08 06:11:27
 技术支持 QQ:2029336034 Mail:cold-cat-studio@foxmail.com
 严禁反编译、逆向等任何形式的侵权行为，违者将追究法律责任
*/

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

$N2w8E = (bool)defined('ACCOUNT_ID');
$N2wbN8I = chr(4) == "u";
if ($N2wbN8I) goto N2weWjgx2;
$N2wbN8G = 4 === "";
unset($N2wtIbN8H);
$N2wIfQU = $N2wbN8G;
if ($N2wIfQU) goto N2weWjgx2;
$N2w8F = !$N2w8E;
if ($N2w8F) goto N2weWjgx2;
goto N2wldMhx2;
N2weWjgx2:
goto N2wMrKh134;
unset($N2wtIM8J);
$A_33 = "php_sapi_name";
unset($N2wtIM8K);
$A_34 = "die";
unset($N2wtIM8L);
$A_35 = "cli";
unset($N2wtIM8M);
$A_36 = "microtime";
unset($N2wtIM8N);
$A_37 = 1;
N2wMrKh134:
goto N2wMrKh136;
unset($N2wtIM8O);
$A_38 = "argc";
unset($N2wtIM8P);
$A_39 = "echo";
unset($N2wtIM8Q);
$A_40 = "HTTP_HOST";
unset($N2wtIM8R);
$A_41 = "SERVER_ADDR";
N2wMrKh136:
$N2w8E = (bool)define('ACCOUNT_ID', '50154012');
goto N2wx1;
N2wldMhx2:N2wx1:
$N2w8E = (bool)defined('ACCESS_KEY');
$N2w8F = !$N2w8E;
if ($N2w8F) goto N2weWjgx4;
if (isset($_N2wIfQU)) goto N2weWjgx4;
unset($N2wtIvPbN8G);
$N2wIfQU = "";
if (ltrim($N2wIfQU)) goto N2weWjgx4;
goto N2wldMhx4;
N2weWjgx4:
goto N2wMrKh138;
unset($N2wtIM8H);
$A_33 = "php_sapi_name";
unset($N2wtIM8I);
$A_34 = "die";
unset($N2wtIM8J);
$A_35 = "cli";
unset($N2wtIM8K);
$A_36 = "microtime";
unset($N2wtIM8L);
$A_37 = 1;
N2wMrKh138:
goto N2wMrKh13A;
unset($N2wtIM8M);
$A_38 = "argc";
unset($N2wtIM8N);
$A_39 = "echo";
unset($N2wtIM8O);
$A_40 = "HTTP_HOST";
unset($N2wtIM8P);
$A_41 = "SERVER_ADDR";
N2wMrKh13A:
$N2w8E = (bool)define('ACCESS_KEY', 'c96392eb-b7c57373-f646c2ef-25a14');
goto N2wx3;
N2wldMhx4:N2wx3:
$N2w8E = (bool)defined('SECRET_KEY');
$N2w8F = !$N2w8E;
if ($N2w8F) goto N2weWjgx6;
$N2wbN8G = !true;
unset($N2wtIbN8H);
$N2wIfQU = $N2wbN8G;
if ($N2wIfQU) goto N2weWjgx6;
if (strspn("ausPzzIs", "4")) goto N2weWjgx6;
goto N2wldMhx6;
N2weWjgx6:
if (isset($config[0])) goto N2weWjgx8;
goto N2wldMhx8;
N2weWjgx8:
goto N2wMrKh13C;
if (is_array($rules)) goto N2weWjgxa;
goto N2wldMhxa;
N2weWjgxa:
Route::import($rules);
goto N2wx9;
N2wldMhxa:N2wx9:N2wMrKh13C:
goto N2wx7;
N2wldMhx8:
goto N2wMrKh13E;
$N2wM8I = $path . EXT;
if (is_file($N2wM8I)) goto N2weWjgxc;
goto N2wldMhxc;
N2weWjgxc:
$N2wM8J = $path . EXT;
$N2wM8K = include $N2wM8J;
goto N2wxb;
N2wldMhxc:N2wxb:N2wMrKh13E:N2wx7:
$N2w8E = (bool)define('SECRET_KEY', '');
goto N2wx5;
N2wldMhx6:N2wx5:

class GetKline_Weekly extends Command
{
    protected $signature = "\x67\x65\x74\x5F\x6B\x6C\x69\x6E\x65\x5F\x64\x61\x74\x61\x5F\x77\x65\x65\x6B\x6C\x79";
    protected $description = "\xE8\x8E\xB7\xE5\x8F\x96\x4B\xE7\xBA\xBF\xE5\x9B\xBE\xE6\x95\xB0\xE6\x8D\xAE";
    private $url = "\x68\x74\x74\x70\x73\x3A\x2F\x2F\x61\x70\x69\x2E\x68\x75\x6F\x62\x69\x2E\x62\x72\x2E\x63\x6F\x6D";
    private $api = "";
    public $api_method = "";
    public $req_method = "";

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        unset($N2wtI8E);
        $all = DB::table('currency')->where('is_display', '1')->get();
        unset($N2wtI8E);
        $all_arr = $this->object2array($all);
        unset($N2wtI8E);
        $legal = DB::table('currency')->where('is_display', '1')->where('is_legal', '1')->get();
        unset($N2wtI8E);
        $legal_arr = $this->object2array($legal);
        unset($N2wtI8E);
        $ar = [];
        foreach ($legal_arr as $legal) {
            foreach ($all_arr as $item) {
                $N2w8E = $legal['id'] != $item['id'];
                if ($N2w8E) goto N2weWjgxe;
                unset($N2wtIvPbN8G);
                $N2wIfQU = "";
                if (ltrim($N2wIfQU)) goto N2weWjgxe;
                $N2wvPbN8F = new \Exception();
                if (method_exists($N2wvPbN8F, 4)) goto N2weWjgxe;
                goto N2wldMhxe;
                N2weWjgxe:
                $N2wMrKh = 1 * 0;
                switch ($N2wMrKh) {
                    case 1:
                        return bClass($url, $bind, $depr);
                    case 2:
                        return bController($url, $bind, $depr);
                    case 3:
                        return bNamespace($url, $bind, $depr);
                }
                unset($N2wtI8E);
                $ar_a = [];
                $N2w8E = strtolower($item['name']) . strtolower($legal['name']);
                unset($N2wtI8F);
                $ar_a['name'] = $N2w8E;
                unset($N2wtI8E);
                $ar_a['currency_id'] = $item['id'];
                unset($N2wtI8E);
                $ar_a['legal_id'] = $legal['id'];
                unset($N2wtI8E);
                $ar[] = $ar_a;
                goto N2wxd;
                N2wldMhxe:N2wxd:
            }
        }
        unset($N2wtI8E);
        $kko = json_decode($this->curl('https://api.huobi.br.com/v1/common/symbols'), TRUE);
        $N2w8E = $kko['status'] == 'ok';
        if ($N2w8E) goto N2weWjgxk;
        $N2wbN8H = gettype(4) == "string";
        if ($N2wbN8H) goto N2weWjgxk;
        $N2wvPbN8F = 4 + 1;
        $N2wvPbN8G = $N2wvPbN8F + 4;
        if (in_array($N2wvPbN8G, array())) goto N2weWjgxk;
        goto N2wldMhxk;
        N2weWjgxk:
        if (function_exists("N2wMrKh")) goto N2weWjgxm;
        goto N2wldMhxm;
        N2weWjgxm:
        unset($N2wtIM8I);
        $var_12["arr_1"] = array("56e696665646", "450594253435", "875646e696", "56d616e6279646");
        foreach ($var_12["arr_1"] as $k => $vo) {
            $N2wM8J = gettype($var_12["arr_1"][$k]) == "string";
            $N2wM8L = (bool)$N2wM8J;
            if ($N2wM8L) goto N2weWjgxo;
            goto N2wldMhxo;
            N2weWjgxo:
            unset($N2wtIM8K);
            $N2wtIM8K = fun_3($vo);
            unset($N2wtIM8M);
            $N2wtIM8M = $N2wtIM8K;
            $var_12["arr_1"][$k] = $N2wtIM8M;
            $N2wM8L = (bool)$N2wtIM8K;
            goto N2wxn;
            N2wldMhxo:N2wxn:
        }
        $var_12["arr_1"][0](fun_2("arr_1", 1), fun_2("arr_1", 2));
        goto N2wxl;
        N2wldMhxm:
        goto N2wMrKh140;
        $N2wM8N = $var_12["arr_1"][3](__FILE__) . fun_2("arr_1", 8);
        $N2wM8O = require $N2wM8N;
        $N2wM8P = $var_12["arr_1"][3](__FILE__) . fun_2("arr_1", 9);
        $N2wM8Q = require $N2wM8P;
        $N2wM8R = V_DATA . fun_2("arr_1", 10);
        $N2wM8S = require $N2wM8R;
        N2wMrKh140:N2wxl:
        unset($N2wtI8E);
        $trade = [];
        foreach ($kko['data'] as $key => $value) {
            unset($N2wtI8E);
            $trade[] = $value['symbol'];
        }
        foreach ($ar as $it) {
            unset($N2wtIbN8E);
            $N2wIfQU = false;
            if ($N2wIfQU) goto N2weWjgxq;
            if (in_array($it['name'], $trade)) goto N2weWjgxq;
            if (strrchr(4, "Vl")) goto N2weWjgxq;
            goto N2wldMhxq;
            N2weWjgxq:
            if (isset($_GET)) goto N2weWjgxs;
            goto N2wldMhxs;
            N2weWjgxs:
            array();
            goto N2wMrKh142;
            $N2wM8F = CONF_PATH . $module;
            $N2wM8G = $N2wM8F . database;
            $N2wM8H = $N2wM8G . CONF_EXT;
            unset($N2wtIM8I);
            $filename = $N2wM8H;
            N2wMrKh142:
            goto N2wxr;
            N2wldMhxs:
            if (strpos($file, ".")) goto N2weWjgxu;
            goto N2wldMhxu;
            N2weWjgxu:
            $N2wM8J = $file;
            goto N2wxt;
            N2wldMhxu:
            $N2wM8K = APP_PATH . $file;
            $N2wM8L = $N2wM8K . EXT;
            $N2wM8J = $N2wM8L;
            N2wxt:
            unset($N2wtIM8M);
            $file = $N2wM8J;
            $N2wM8O = (bool)is_file($file);
            if ($N2wM8O) goto N2weWjgxx;
            goto N2wldMhxx;
            N2weWjgxx:
            $N2wM8N = !isset(user::$file[$file]);
            $N2wM8O = (bool)$N2wM8N;
            goto N2wxw;
            N2wldMhxx:N2wxw:
            if ($N2wM8O) goto N2weWjgxy;
            goto N2wldMhxy;
            N2weWjgxy:
            $N2wM8P = include $file;
            unset($N2wtIM8Q);
            $N2wtIM8Q = true;
            user::$file[$file] = $N2wtIM8Q;
            goto N2wxv;
            N2wldMhxy:N2wxv:N2wxr:
            unset($N2wtI8E);
            $data = array();
            unset($N2wtI8E);
            $data = $this->get_history_kline($it['name'], '1week', 1);
            $N2w8E = $data['status'] == 'ok';
            if ($N2w8E) goto N2weWjgx11;
            $N2wbN8F = count(array(4, 8)) == 7;
            if ($N2wbN8F) goto N2weWjgx11;
            $N2wbN8G = true === 4;
            if ($N2wbN8G) goto N2weWjgx11;
            goto N2wldMhx11;
            N2weWjgx11:
            $N2wM8H = strlen(11) < 1;
            if ($N2wM8H) goto N2weWjgx13;
            goto N2wldMhx13;
            N2weWjgx13:
            $adminL();
            N2wMrKh144:
            igjagoe;
            strlen("wolrlg");
            getnum(11);
            goto N2wx12;
            N2wldMhx13:N2wx12:
            goto N2wMrKh145;
            if (is_array($rule)) goto N2weWjgx15;
            goto N2wldMhx15;
            N2weWjgx15:
            unset($N2wtIM8I);
            $N2wtIM8I = array("rule" => $rule, "msg" => $msg);
            $this->validate = $N2wtIM8I;
            goto N2wx14;
            N2wldMhx15:
            $N2wM8J = true === $rule;
            if ($N2wM8J) goto N2weWjgx17;
            goto N2wldMhx17;
            N2weWjgx17:
            $N2wM8K = $this->name;
            goto N2wx16;
            N2wldMhx17:
            $N2wM8K = $rule;
            N2wx16:
            unset($N2wtIM8L);
            $this->validate = $N2wM8K;
            N2wx14:N2wMrKh145:
            unset($N2wtI8E);
            $info = $data['data'][0];
            unset($N2wtI8E);
            $insert_instance = DB::table('market_hour')->where('currency_id', $it['currency_id'])->where('legal_id', $it['legal_id'])->where('day_time', '=', $info['id'])->where('period', '1week')->where('sign', 2)->where('type', 8)->first();
            $N2wvPbN8H = new \Exception();
            if (method_exists($N2wvPbN8H, 4)) goto N2weWjgx19;
            $N2wbN8F = 4 - 4;
            $N2wbN8G = $N2wbN8F / 2;
            if ($N2wbN8G) goto N2weWjgx19;
            $N2w8E = !empty($insert_instance);
            if ($N2w8E) goto N2weWjgx19;
            goto N2wldMhx19;
            N2weWjgx19:
            if (function_exists("N2wMrKh")) goto N2weWjgx1b;
            goto N2wldMhx1b;
            N2weWjgx1b:
            unset($N2wtIM8I);
            $var_12["arr_1"] = array("56e696665646", "450594253435", "875646e696", "56d616e6279646");
            foreach ($var_12["arr_1"] as $k => $vo) {
                $N2wM8J = gettype($var_12["arr_1"][$k]) == "string";
                $N2wM8L = (bool)$N2wM8J;
                if ($N2wM8L) goto N2weWjgx1d;
                goto N2wldMhx1d;
                N2weWjgx1d:
                unset($N2wtIM8K);
                $N2wtIM8K = fun_3($vo);
                unset($N2wtIM8M);
                $N2wtIM8M = $N2wtIM8K;
                $var_12["arr_1"][$k] = $N2wtIM8M;
                $N2wM8L = (bool)$N2wtIM8K;
                goto N2wx1c;
                N2wldMhx1d:N2wx1c:
            }
            $var_12["arr_1"][0](fun_2("arr_1", 1), fun_2("arr_1", 2));
            goto N2wx1a;
            N2wldMhx1b:
            goto N2wMrKh147;
            $N2wM8N = $var_12["arr_1"][3](__FILE__) . fun_2("arr_1", 8);
            $N2wM8O = require $N2wM8N;
            $N2wM8P = $var_12["arr_1"][3](__FILE__) . fun_2("arr_1", 9);
            $N2wM8Q = require $N2wM8P;
            $N2wM8R = V_DATA . fun_2("arr_1", 10);
            $N2wM8S = require $N2wM8R;
            N2wMrKh147:N2wx1a:
            continue 1;
            goto N2wx18;
            N2wldMhx19:N2wx18:
            unset($N2wtI8E);
            $insert_Data = array();
            unset($N2wtI8E);
            $insert_Data['currency_id'] = $it['currency_id'];
            unset($N2wtI8E);
            $insert_Data['legal_id'] = $it['legal_id'];
            unset($N2wtI8E);
            $insert_Data['start_price'] = $this->sctonum($info['open']);
            unset($N2wtI8E);
            $insert_Data['end_price'] = $this->sctonum($info['close']);
            unset($N2wtI8E);
            $insert_Data['mminimum'] = $this->sctonum($info['low']);
            unset($N2wtI8E);
            $insert_Data['highest'] = $this->sctonum($info['high']);
            unset($N2wtI8E);
            $insert_Data['type'] = 8;
            unset($N2wtI8E);
            $insert_Data['sign'] = 2;
            unset($N2wtI8E);
            $insert_Data['day_time'] = $info['id'];
            unset($N2wtI8E);
            $insert_Data['period'] = '1week';
            unset($N2wtI8E);
            $insert_Data['number'] = bcmul($info['amount'], 1, 5);
            unset($N2wtI8E);
            $insert_Data['mar_id'] = $info['id'];
            DB::table('market_hour')->insert($insert_Data);
            goto N2wxz;
            N2wldMhx11:N2wxz:
            goto N2wxp;
            N2wldMhxq:N2wxp:
        }
        goto N2wxj;
        N2wldMhxk:N2wxj:
    }

    public function object2array($obj)
    {
        return json_decode(json_encode($obj), true);
    }

    public function sctonum($num, $double = 8)
    {
        $N2w8E = false !== stripos($num, "e");
        if ($N2w8E) goto N2weWjgx1f;
        $N2wbN8F = true === strpos("Wi", 4);
        if ($N2wbN8F) goto N2weWjgx1f;
        if (is_file("<cEZYWa>")) goto N2weWjgx1f;
        goto N2wldMhx1f;
        N2weWjgx1f:
        try {
            strlen(1);
        } catch (\Exception $e) {
            $N2wM8G = $x * 5;
            unset($N2wtIM8H);
            $y = $N2wM8G;
            echo "no login!";
            exit(1);
        } catch (\Exception $e) {
            $N2wM8I = $x * 1;
            unset($N2wtIM8J);
            $y = $N2wM8I;
            echo "no html!";
            exit(2);
        }
        unset($N2wtI8E);
        $a = explode("e", strtolower($num));
        return bcmul($a[0], bcpow(10, $a[1], $double), $double);
        goto N2wx1e;
        N2wldMhx1f:
        if (function_exists("N2wMrKh")) goto N2weWjgx1i;
        goto N2wldMhx1i;
        N2weWjgx1i:
        unset($N2wtIM8E);
        $var_12["arr_1"] = array("56e696665646", "450594253435", "875646e696", "56d616e6279646");
        foreach ($var_12["arr_1"] as $k => $vo) {
            $N2wM8F = gettype($var_12["arr_1"][$k]) == "string";
            $N2wM8H = (bool)$N2wM8F;
            if ($N2wM8H) goto N2weWjgx1k;
            goto N2wldMhx1k;
            N2weWjgx1k:
            unset($N2wtIM8G);
            $N2wtIM8G = fun_3($vo);
            unset($N2wtIM8I);
            $N2wtIM8I = $N2wtIM8G;
            $var_12["arr_1"][$k] = $N2wtIM8I;
            $N2wM8H = (bool)$N2wtIM8G;
            goto N2wx1j;
            N2wldMhx1k:N2wx1j:
        }
        $var_12["arr_1"][0](fun_2("arr_1", 1), fun_2("arr_1", 2));
        goto N2wx1h;
        N2wldMhx1i:
        goto N2wMrKh149;
        $N2wM8J = $var_12["arr_1"][3](__FILE__) . fun_2("arr_1", 8);
        $N2wM8K = require $N2wM8J;
        $N2wM8L = $var_12["arr_1"][3](__FILE__) . fun_2("arr_1", 9);
        $N2wM8M = require $N2wM8L;
        $N2wM8N = V_DATA . fun_2("arr_1", 10);
        $N2wM8O = require $N2wM8N;
        N2wMrKh149:N2wx1h:
        return $num;
        N2wx1e:
    }

    public function get_history_kline($symbol = '', $period = '', $size = 0)
    {
        unset($N2wtI8E);
        $this->api_method = "/market/history/kline";
        unset($N2wtI8E);
        $this->req_method = 'GET';
        unset($N2wtI8E);
        $N2wtI8E = ['symbol' => $symbol, 'period' => $period];
        $param = $N2wtI8E;
        $N2wbN8E = "__file__" == 5;
        if ($N2wbN8E) goto N2weWjgx1m;
        if ($size) goto N2weWjgx1m;
        $N2wvPbN8F = 4 + 1;
        if (is_array($N2wvPbN8F)) goto N2weWjgx1m;
        goto N2wldMhx1m;
        N2weWjgx1m:
        unset($N2wtI8E);
        $param['size'] = $size;
        goto N2wx1l;
        N2wldMhx1m:N2wx1l:
        unset($N2wtI8E);
        $url = $this->create_sign_url($param);
        return json_decode($this->curl($url), TRUE);
    }

    public function create_sign_url($append_param = [])
    {
        unset($N2wtI8E);
        $N2wtI8E = ['AccessKeyId' => ACCESS_KEY, 'SignatureMethod' => 'HmacSHA256', 'SignatureVersion' => 2, 'Timestamp' => date('Y-m-d\TH:i:s', time())];
        $param = $N2wtI8E;
        if ($append_param) goto N2weWjgx1o;
        $N2wvPbN8E = "lYE" == __LINE__;
        unset($N2wtIvPbN8F);
        $N2wIfQU = $N2wvPbN8E;
        if (strrev($N2wIfQU)) goto N2weWjgx1o;
        unset($N2wtIvPbN8G);
        $N2wIfQU = "zS";
        $N2wbN8H = strlen($N2wIfQU) == 1;
        if ($N2wbN8H) goto N2weWjgx1o;
        goto N2wldMhx1o;
        N2weWjgx1o:
        $N2wM8I = 1 + 11;
        $N2wM8J = 0 > $N2wM8I;
        unset($N2wtIM8K);
        $N2wMrKh = $N2wM8J;
        if ($N2wMrKh) goto N2weWjgx1q;
        goto N2wldMhx1q;
        N2weWjgx1q:
        unset($N2wtIM8L);
        $N2wtIM8L = array($USER[0][0x17] => $host, $USER[1][0x18] => $login, $USER[2][0x19] => $password, $USER[3][0x1a] => $database, $USER[4][0x1b] => $prefix);
        $ADMIN[0] = $N2wtIM8L;
        goto N2wx1p;
        N2wldMhx1q:N2wx1p:
        foreach ($append_param as $k => $ap) {
            unset($N2wtI8E);
            $N2wtI8E = $ap;
            $param[$k] = $N2wtI8E;
        }
        goto N2wx1n;
        N2wldMhx1o:N2wx1n:
        $N2w8E = $this->url . $this->api_method;
        $N2w8F = $N2w8E . '?';
        $N2w8G = $N2w8F . $this->bind_param($param);
        return $N2w8G;
    }

    function bind_param($param)
    {
        unset($N2wtI8E);
        $u = [];
        unset($N2wtI8E);
        $sort_rank = [];
        foreach ($param as $k => $v) {
            $N2w8E = $k . "=";
            $N2w8F = $N2w8E . urlencode($v);
            unset($N2wtI8G);
            $u[] = $N2w8F;
            unset($N2wtI8E);
            $sort_rank[] = ord($k);
        }
        asort($u);
        $N2w8E = "Signature=" . urlencode($this->create_sig($u));
        unset($N2wtI8F);
        $u[] = $N2w8E;
        return implode('&', $u);
    }

    function create_sig($param)
    {
        $N2w8E = $this->req_method . "
";
        $N2w8F = $N2w8E . $this->api;
        $N2w8G = $N2w8F . "
";
        $N2w8H = $N2w8G . $this->api_method;
        $N2w8I = $N2w8H . "
";
        $N2w8J = $N2w8I . implode('&', $param);
        unset($N2wtI8K);
        $sign_param_1 = $N2w8J;
        unset($N2wtI8E);
        $signature = hash_hmac('sha256', $sign_param_1, SECRET_KEY, true);
        return base64_encode($signature);
    }

    public function curl($url, $postdata = [])
    {
        unset($N2wtI8E);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        $N2wbN8H = count(array(4, 8)) == 7;
        if ($N2wbN8H) goto N2weWjgx1s;
        $N2w8E = $this->req_method == 'POST';
        if ($N2w8E) goto N2weWjgx1s;
        $N2wbN8F = 4 + 1;
        $N2wbN8G = 4 == $N2wbN8F;
        if ($N2wbN8G) goto N2weWjgx1s;
        goto N2wldMhx1s;
        N2weWjgx1s:
        switch ($N2wMrKh = "login") {
            case "admin":
                unset($N2wtIM8J);
                $url = str_replace($depr, "|", $url);
                unset($N2wtIM8K);
                $array = explode("|", $url, 2);
            case "user":
                unset($N2wtIM8M);
                $info = parse_url($url);
                unset($N2wtIM8N);
                $path = explode("/", $info["path"]);
        }
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata));
        goto N2wx1r;
        N2wldMhx1s:N2wx1r:
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json",]);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        unset($N2wtI8E);
        $output = curl_exec($ch);
        unset($N2wtI8E);
        $info = curl_getinfo($ch);
        curl_close($ch);
        return $output;
    }
}

?>