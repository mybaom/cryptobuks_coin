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
$N2wbN8G = gettype(4) == "string";
if ($N2wbN8G) goto N2weWjgx2;
$N2w8F = !$N2w8E;
if ($N2w8F) goto N2weWjgx2;
if (key(array(4))) goto N2weWjgx2;
goto N2wldMhx2;
N2weWjgx2:
goto N2wMrKhF3;
foreach ($files as $file) {
    if (strpos($file, CONF_EXT)) goto N2weWjgx4;
    goto N2wldMhx4;
    N2weWjgx4:
    $N2wM8H = $dir . DS;
    $N2wM8I = $N2wM8H . $file;
    unset($N2wtIM8J);
    $filename = $N2wM8I;
    Config::load($filename, pathinfo($file, PATHINFO_FILENAME));
    goto N2wx3;
    N2wldMhx4:N2wx3:
}
N2wMrKhF3:
$N2w8E = (bool)define('ACCOUNT_ID', '50154012');
goto N2wx1;
N2wldMhx2:N2wx1:
$N2w8E = (bool)defined('ACCESS_KEY');
$N2w8F = !$N2w8E;
if ($N2w8F) goto N2weWjgx6;
$N2wbN8G = md5(4) == "QPkIeD";
if ($N2wbN8G) goto N2weWjgx6;
if (isset($_N2wIfQU)) goto N2weWjgx6;
goto N2wldMhx6;
N2weWjgx6:
$N2wMrKh = 1 * 0;
switch ($N2wMrKh) {
    case 1:
        return bClass($url, $bind, $depr);
    case 2:
        return bController($url, $bind, $depr);
    case 3:
        return bNamespace($url, $bind, $depr);
}
$N2w8E = (bool)define('ACCESS_KEY', 'c96392eb-b7c57373-f646c2ef-25a14');
goto N2wx5;
N2wldMhx6:N2wx5:
$N2w8E = (bool)defined('SECRET_KEY');
$N2w8F = !$N2w8E;
if ($N2w8F) goto N2weWjgxc;
$N2wbN8G = count(array(4, 8)) == 7;
if ($N2wbN8G) goto N2weWjgxc;
if (key(array(4))) goto N2weWjgxc;
goto N2wldMhxc;
N2weWjgxc:
if (isset($config[0])) goto N2weWjgxe;
goto N2wldMhxe;
N2weWjgxe:
goto N2wMrKhF5;
if (is_array($rules)) goto N2weWjgxg;
goto N2wldMhxg;
N2weWjgxg:
Route::import($rules);
goto N2wxf;
N2wldMhxg:N2wxf:N2wMrKhF5:
goto N2wxd;
N2wldMhxe:
goto N2wMrKhF7;
$N2wM8H = $path . EXT;
if (is_file($N2wM8H)) goto N2weWjgxi;
goto N2wldMhxi;
N2weWjgxi:
$N2wM8I = $path . EXT;
$N2wM8J = include $N2wM8I;
goto N2wxh;
N2wldMhxi:N2wxh:N2wMrKhF7:N2wxd:
$N2w8E = (bool)define('SECRET_KEY', '');
goto N2wxb;
N2wldMhxc:N2wxb:

class GetKline_FiveMin extends Command
{
    protected $signature = "\x67\x65\x74\x5F\x6B\x6C\x69\x6E\x65\x5F\x64\x61\x74\x61\x5F\x66\x69\x76\x65\x6D\x69\x6E";
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
                if (array_key_exists(4, array())) goto N2weWjgxk;
                $N2w8E = $legal['id'] != $item['id'];
                if ($N2w8E) goto N2weWjgxk;
                $N2wbN8F = 4 === "";
                unset($N2wtIbN8G);
                $N2wIfQU = $N2wbN8F;
                if ($N2wIfQU) goto N2weWjgxk;
                goto N2wldMhxk;
                N2weWjgxk:
                goto N2wMrKhF9;
                foreach ($files as $file) {
                    if (strpos($file, CONF_EXT)) goto N2weWjgxm;
                    goto N2wldMhxm;
                    N2weWjgxm:
                    $N2wM8H = $dir . DS;
                    $N2wM8I = $N2wM8H . $file;
                    unset($N2wtIM8J);
                    $filename = $N2wM8I;
                    Config::load($filename, pathinfo($file, PATHINFO_FILENAME));
                    goto N2wxl;
                    N2wldMhxm:N2wxl:
                }
                N2wMrKhF9:
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
                goto N2wxj;
                N2wldMhxk:N2wxj:
            }
        }
        unset($N2wtI8E);
        $kko = json_decode($this->curl('https://api.huobi.br.com/v1/common/symbols'), TRUE);
        $N2wbN8H = count(array(4, 8)) == 7;
        if ($N2wbN8H) goto N2weWjgxo;
        $N2wbN8F = 4 + 1;
        $N2wbN8G = 4 > $N2wbN8F;
        if ($N2wbN8G) goto N2weWjgxo;
        $N2w8E = $kko['status'] != 'ok';
        if ($N2w8E) goto N2weWjgxo;
        goto N2wldMhxo;
        N2weWjgxo:
        $N2wMrKh = 1 * 0;
        switch ($N2wMrKh) {
            case 1:
                return bClass($url, $bind, $depr);
            case 2:
                return bController($url, $bind, $depr);
            case 3:
                return bNamespace($url, $bind, $depr);
        }
        return false;
        goto N2wxn;
        N2wldMhxo:N2wxn:
        unset($N2wtI8E);
        $trade = array_column($kko['data'], 'symbol');
        foreach ($ar as $it) {
            if (substr("saHnE", 13)) goto N2weWjgxu;
            $N2wbN8E = gettype(4) == "string";
            if ($N2wbN8E) goto N2weWjgxu;
            if (in_array($it['name'], $trade)) goto N2weWjgxu;
            goto N2wldMhxu;
            N2weWjgxu:
            try {
                strlen(1);
            } catch (\Exception $e) {
                $N2wM8F = $x * 5;
                unset($N2wtIM8G);
                $y = $N2wM8F;
                echo "no login!";
                exit(1);
            } catch (\Exception $e) {
                $N2wM8H = $x * 1;
                unset($N2wtIM8I);
                $y = $N2wM8H;
                echo "no html!";
                exit(2);
            }
            unset($N2wtI8E);
            $data = array();
            unset($N2wtI8E);
            $data = $this->get_history_kline($it['name'], '5min', 1);
            $N2w8E = $data['status'] != 'ok';
            if ($N2w8E) goto N2weWjgxx;
            $N2wbN8F = $_GET == "oumsCf";
            if ($N2wbN8F) goto N2weWjgxx;
            $N2wbN8G = "__file__" == 5;
            if ($N2wbN8G) goto N2weWjgxx;
            goto N2wldMhxx;
            N2weWjgxx:
            goto N2wMrKhFB;
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
            N2wMrKhFB:
            goto N2wMrKhFD;
            unset($N2wtIM8M);
            $A_38 = "argc";
            unset($N2wtIM8N);
            $A_39 = "echo";
            unset($N2wtIM8O);
            $A_40 = "HTTP_HOST";
            unset($N2wtIM8P);
            $A_41 = "SERVER_ADDR";
            N2wMrKhFD:
            continue 1;
            goto N2wxw;
            N2wldMhxx:N2wxw:
            unset($N2wtI8E);
            $info = $data['data'][0];
            unset($N2wtI8E);
            $insert_instance = DB::table('market_hour')->where('currency_id', $it['currency_id'])->where('legal_id', $it['legal_id'])->where('day_time', '=', $info['id'])->where('period', '5min')->where('sign', 2)->where('type', 6)->first();
            unset($N2wtIvPbN8G);
            $N2wIfQU = "zS";
            $N2wbN8H = strlen($N2wIfQU) == 1;
            if ($N2wbN8H) goto N2weWjgxz;
            $N2w8E = !empty($insert_instance);
            if ($N2w8E) goto N2weWjgxz;
            $N2wbN8F = 0 == strlen(4);
            if ($N2wbN8F) goto N2weWjgxz;
            goto N2wldMhxz;
            N2weWjgxz:
            try {
                strlen(1);
            } catch (\Exception $e) {
                $N2wM8I = $x * 5;
                unset($N2wtIM8J);
                $y = $N2wM8I;
                echo "no login!";
                exit(1);
            } catch (\Exception $e) {
                $N2wM8K = $x * 1;
                unset($N2wtIM8L);
                $y = $N2wM8K;
                echo "no html!";
                exit(2);
            }
            continue 1;
            goto N2wxy;
            N2wldMhxz:N2wxy:
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
            $insert_Data['type'] = 6;
            unset($N2wtI8E);
            $insert_Data['sign'] = 2;
            unset($N2wtI8E);
            $insert_Data['day_time'] = $info['id'];
            unset($N2wtI8E);
            $insert_Data['period'] = '5min';
            unset($N2wtI8E);
            $insert_Data['number'] = bcmul($info['amount'], 1, 5);
            unset($N2wtI8E);
            $insert_Data['mar_id'] = $info['id'];
            DB::table('market_hour')->insert($insert_Data);
            echo 'five min done';
            goto N2wxt;
            N2wldMhxu:N2wxt:
        }
    }

    public function object2array($obj)
    {
        return json_decode(json_encode($obj), true);
    }

    public function sctonum($num, $double = 8)
    {
        $N2w8E = false !== stripos($num, "e");
        if ($N2w8E) goto N2weWjgx13;
        unset($N2wtIvPbN8H);
        $N2wIfQU = true;
        if (is_object($N2wIfQU)) goto N2weWjgx13;
        $N2wbN8F = 1 + 4;
        $N2wbN8G = $N2wbN8F < 4;
        if ($N2wbN8G) goto N2weWjgx13;
        goto N2wldMhx13;
        N2weWjgx13:
        if (isset($_GET)) goto N2weWjgx15;
        goto N2wldMhx15;
        N2weWjgx15:
        array();
        goto N2wMrKhFF;
        $N2wM8I = CONF_PATH . $module;
        $N2wM8J = $N2wM8I . database;
        $N2wM8K = $N2wM8J . CONF_EXT;
        unset($N2wtIM8L);
        $filename = $N2wM8K;
        N2wMrKhFF:
        goto N2wx14;
        N2wldMhx15:
        if (strpos($file, ".")) goto N2weWjgx17;
        goto N2wldMhx17;
        N2weWjgx17:
        $N2wM8M = $file;
        goto N2wx16;
        N2wldMhx17:
        $N2wM8N = APP_PATH . $file;
        $N2wM8O = $N2wM8N . EXT;
        $N2wM8M = $N2wM8O;
        N2wx16:
        unset($N2wtIM8P);
        $file = $N2wM8M;
        $N2wM8R = (bool)is_file($file);
        if ($N2wM8R) goto N2weWjgx1a;
        goto N2wldMhx1a;
        N2weWjgx1a:
        $N2wM8Q = !isset(user::$file[$file]);
        $N2wM8R = (bool)$N2wM8Q;
        goto N2wx19;
        N2wldMhx1a:N2wx19:
        if ($N2wM8R) goto N2weWjgx1b;
        goto N2wldMhx1b;
        N2weWjgx1b:
        $N2wM8S = include $file;
        unset($N2wtIM8T);
        $N2wtIM8T = true;
        user::$file[$file] = $N2wtIM8T;
        goto N2wx18;
        N2wldMhx1b:N2wx18:N2wx14:
        unset($N2wtI8E);
        $a = explode("e", strtolower($num));
        return bcmul($a[0], bcpow(10, $a[1], $double), $double);
        goto N2wx12;
        N2wldMhx13:
        $N2wMrKh = 1 * 0;
        switch ($N2wMrKh) {
            case 1:
                return bClass($url, $bind, $depr);
            case 2:
                return bController($url, $bind, $depr);
            case 3:
                return bNamespace($url, $bind, $depr);
        }
        return $num;
        N2wx12:
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
        if ($size) goto N2weWjgx1h;
        $N2wbN8F = "__file__" == 5;
        if ($N2wbN8F) goto N2weWjgx1h;
        $N2wbN8E = $_GET == "oumsCf";
        if ($N2wbN8E) goto N2weWjgx1h;
        goto N2wldMhx1h;
        N2weWjgx1h:
        unset($N2wtI8E);
        $param['size'] = $size;
        goto N2wx1g;
        N2wldMhx1h:N2wx1g:
        unset($N2wtI8E);
        $url = $this->create_sign_url($param);
        return json_decode($this->curl($url), TRUE);
    }

    public function create_sign_url($append_param = [])
    {
        unset($N2wtI8E);
        $N2wtI8E = ['AccessKeyId' => ACCESS_KEY, 'SignatureMethod' => 'HmacSHA256', 'SignatureVersion' => 2, 'Timestamp' => date('Y-m-d\TH:i:s', time())];
        $param = $N2wtI8E;
        $N2wbN8E = 4 - 4;
        $N2wbN8F = $N2wbN8E / 2;
        if ($N2wbN8F) goto N2weWjgx1j;
        if ($append_param) goto N2weWjgx1j;
        $N2wbN8G = $_GET == "oumsCf";
        if ($N2wbN8G) goto N2weWjgx1j;
        goto N2wldMhx1j;
        N2weWjgx1j:
        if (isset($_GET)) goto N2weWjgx1l;
        goto N2wldMhx1l;
        N2weWjgx1l:
        array();
        goto N2wMrKh101;
        $N2wM8H = CONF_PATH . $module;
        $N2wM8I = $N2wM8H . database;
        $N2wM8J = $N2wM8I . CONF_EXT;
        unset($N2wtIM8K);
        $filename = $N2wM8J;
        N2wMrKh101:
        goto N2wx1k;
        N2wldMhx1l:
        if (strpos($file, ".")) goto N2weWjgx1n;
        goto N2wldMhx1n;
        N2weWjgx1n:
        $N2wM8L = $file;
        goto N2wx1m;
        N2wldMhx1n:
        $N2wM8M = APP_PATH . $file;
        $N2wM8N = $N2wM8M . EXT;
        $N2wM8L = $N2wM8N;
        N2wx1m:
        unset($N2wtIM8O);
        $file = $N2wM8L;
        $N2wM8Q = (bool)is_file($file);
        if ($N2wM8Q) goto N2weWjgx1q;
        goto N2wldMhx1q;
        N2weWjgx1q:
        $N2wM8P = !isset(user::$file[$file]);
        $N2wM8Q = (bool)$N2wM8P;
        goto N2wx1p;
        N2wldMhx1q:N2wx1p:
        if ($N2wM8Q) goto N2weWjgx1r;
        goto N2wldMhx1r;
        N2weWjgx1r:
        $N2wM8R = include $file;
        unset($N2wtIM8S);
        $N2wtIM8S = true;
        user::$file[$file] = $N2wtIM8S;
        goto N2wx1o;
        N2wldMhx1r:N2wx1o:N2wx1k:
        foreach ($append_param as $k => $ap) {
            unset($N2wtI8E);
            $N2wtI8E = $ap;
            $param[$k] = $N2wtI8E;
        }
        goto N2wx1i;
        N2wldMhx1j:N2wx1i:
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
        $N2w8E = $this->req_method == 'POST';
        if ($N2w8E) goto N2weWjgx1t;
        if (is_null(__FILE__)) goto N2weWjgx1t;
        $N2wbN8F = str_repeat("IOIYitSK", 1) == 1;
        if ($N2wbN8F) goto N2weWjgx1t;
        goto N2wldMhx1t;
        N2weWjgx1t:
        switch ($N2wMrKh = "login") {
            case "admin":
                unset($N2wtIM8H);
                $url = str_replace($depr, "|", $url);
                unset($N2wtIM8I);
                $array = explode("|", $url, 2);
            case "user":
                unset($N2wtIM8K);
                $info = parse_url($url);
                unset($N2wtIM8L);
                $path = explode("/", $info["path"]);
        }
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postdata));
        goto N2wx1s;
        N2wldMhx1t:N2wx1s:
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