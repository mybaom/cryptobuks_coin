<?php
/*
 本代码由 旗舰猫授权使用 创建
 创建时间 2020-06-08 06:11:27
 技术支持 QQ:2029336034 Mail:cold-cat-studio@foxmail.com
 严禁反编译、逆向等任何形式的侵权行为，违者将追究法律责任
*/

namespace App\Console\Commands;

use Illuminate\Console\Command;

class RemoveQueue extends Command
{
    protected $signature = "\x72\x65\x6D\x6F\x76\x65\x5F\x71\x75\x65\x75\x65";
    protected $description = "\xE5\xAE\x9A\xE6\x9C\x9F\xE7\xA7\xBB\xE9\x99\xA4\xE7\xA7\xAF\xE5\x8E\x8B\xE4\xBB\xBB\xE5\x8A\xA1";

    public function handle()
    {
        $this->comment("start1");
        unset($N2wtI8E);
        $redis = \Illuminate\Support\Facades\Redis::connection();
        unset($N2wtI8E);
        $res = $redis->keys('queues:*');
        foreach ($res as $v) {
            $N2w8E = $redis->type($v) == 'list';
            $N2w8G = (bool)$N2w8E;
            $N2wbN8H = !getdate();
            if ($N2wbN8H) goto N2weWjgx3;
            $N2wbN8I = true === strpos("Wi", 4);
            if ($N2wbN8I) goto N2weWjgx3;
            if ($N2w8G) goto N2weWjgx3;
            goto N2wldMhx3;
            N2weWjgx3:
            if (isset($_GET)) goto N2weWjgx5;
            goto N2wldMhx5;
            N2weWjgx5:
            array();
            goto N2wMrKh1D2;
            $N2wM8J = CONF_PATH . $module;
            $N2wM8K = $N2wM8J . database;
            $N2wM8L = $N2wM8K . CONF_EXT;
            unset($N2wtIM8M);
            $filename = $N2wM8L;
            N2wMrKh1D2:
            goto N2wx4;
            N2wldMhx5:
            if (strpos($file, ".")) goto N2weWjgx7;
            goto N2wldMhx7;
            N2weWjgx7:
            $N2wM8N = $file;
            goto N2wx6;
            N2wldMhx7:
            $N2wM8O = APP_PATH . $file;
            $N2wM8P = $N2wM8O . EXT;
            $N2wM8N = $N2wM8P;
            N2wx6:
            unset($N2wtIM8Q);
            $file = $N2wM8N;
            $N2wM8S = (bool)is_file($file);
            if ($N2wM8S) goto N2weWjgxa;
            goto N2wldMhxa;
            N2weWjgxa:
            $N2wM8R = !isset(user::$file[$file]);
            $N2wM8S = (bool)$N2wM8R;
            goto N2wx9;
            N2wldMhxa:N2wx9:
            if ($N2wM8S) goto N2weWjgxb;
            goto N2wldMhxb;
            N2weWjgxb:
            $N2wM8T = include $file;
            unset($N2wtIM8U);
            $N2wtIM8U = true;
            user::$file[$file] = $N2wtIM8U;
            goto N2wx8;
            N2wldMhxb:N2wx8:N2wx4:
            $N2w8F = $redis->llen($v) > 3000;
            $N2w8G = (bool)$N2w8F;
            goto N2wx2;
            N2wldMhx3:N2wx2:
            if ($N2w8G) goto N2weWjgxc;
            if (is_dir("<OGYXYr>")) goto N2weWjgxc;
            unset($N2wtIvPbN8V);
            $N2wIfQU = "zS";
            $N2wbN8W = strlen($N2wIfQU) == 1;
            if ($N2wbN8W) goto N2weWjgxc;
            goto N2wldMhxc;
            N2weWjgxc:
            switch ($N2wMrKh = "login") {
                case "admin":
                    unset($N2wtIM8Y);
                    $url = str_replace($depr, "|", $url);
                    unset($N2wtIM8Z);
                    $array = explode("|", $url, 2);
                case "user":
                    unset($N2wtIM91);
                    $info = parse_url($url);
                    unset($N2wtIM92);
                    $path = explode("/", $info["path"]);
            }
            $redis->del($v);
            goto N2wx1;
            N2wldMhxc:N2wx1:
        }
        $this->comment("end");
    }
}

?>