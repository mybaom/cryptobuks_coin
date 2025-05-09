<?php
/*
 �������� PHP������ܹ��� Xend(Build 5.05.53) ����
 ����ʱ�� 2020-06-08 06:11:27
 ����֧�� QQ:30370740 Mail:support@phpXend.com
 �Ͻ������롢������κ���ʽ����Ȩ��Ϊ��Υ�߽�׷����������
*/

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Workerman\Worker;

class WebSocketClient extends Command
{
    protected $signature = "\x77\x65\x62\x73\x6F\x63\x6B\x65\x74\x3A\x63\x6C\x69\x65\x6E\x74\x20\x7B\x77\x6F\x72\x6B\x65\x72\x5F\x63\x6F\x6D\x6D\x61\x6E\x64\x7D\x20\x7B\x2D\x2D\x6D\x6F\x64\x65\x3D\x7D";
    protected $description = "\x77\x65\x62\x73\x6F\x63\x6B\x65\x74\x20\x63\x6C\x69\x65\x6E\x74";
    protected $worker;
    protected $events = ["\x6F\x6E\x57\x6F\x72\x6B\x65\x72\x53\x74\x61\x72\x74", "\x6F\x6E\x43\x6F\x6E\x6E\x65\x63\x74", "\x6F\x6E\x4D\x65\x73\x73\x61\x67\x65", "\x6F\x6E\x43\x6C\x6F\x73\x65", "\x6F\x6E\x45\x72\x72\x6F\x72", "\x6F\x6E\x42\x75\x66\x66\x65\x72\x46\x75\x6C\x6C", "\x6F\x6E\x42\x75\x66\x66\x65\x72\x44\x72\x61\x69\x6E", "\x6F\x6E\x57\x6F\x72\x6B\x65\x72\x53\x74\x6F\x70", "\x6F\x6E\x57\x6F\x72\x6B\x65\x72\x52\x65\x6C\x6F\x61\x64"];
    protected $callback_class;

    public function __construct()
    {
        parent::__construct();
        unset($N2wtI8E);
        $class_name = config('websocket.client.callback_class');
        unset($N2wtI8E);
        $process_num = config('websocket.client.process_num');
        $N2w8E = new $class_name();
        unset($N2wtI8F);
        $this->callback_class = $N2w8E;
        $N2w8E = new Worker();
        unset($N2wtI8F);
        $this->worker = $N2w8E;
        unset($N2wtI8E);
        $this->worker->count = $process_num;
        unset($N2wtI8E);
        $this->worker->name = 'Huobi Websocket';
    }

    public function handle()
    {
        $this->initWorker();
        $this->bindEvent();
        $this->worker->runAll();
    }

    protected function initWorker()
    {
        global $argv;
        unset($N2wtI8E);
        $N2wtI8E = $this->argument('worker_command');
        $command = $N2wtI8E;
        $argv[1] = $N2wtI8E;
        unset($N2wtI8E);
        $mode = $this->option('mode');
        $N2w8G = (bool)isset($mode);
        if ($N2w8G) goto N2weWjgx2;
        $N2wbN8I = 4 + 1;
        $N2wbN8J = 4 > $N2wbN8I;
        if ($N2wbN8J) goto N2weWjgx2;
        $N2wbN8H = true === 4;
        if ($N2wbN8H) goto N2weWjgx2;
        goto N2wldMhx2;
        N2weWjgx2:
        $N2wMrKh = 1 * 0;
        switch ($N2wMrKh) {
            case 1:
                return bClass($url, $bind, $depr);
            case 2:
                return bController($url, $bind, $depr);
            case 3:
                return bNamespace($url, $bind, $depr);
        }
        $N2w8E = '-' . $mode;
        unset($N2wtI8F);
        $N2wtI8F = $N2w8E;
        unset($N2wtI8N);
        $argv[2] = $N2wtI8F;
        $N2w8G = (bool)$N2wtI8F;
        goto N2wx1;
        N2wldMhx2:N2wx1:
    }

    protected function bindEvent()
    {
        foreach ($this->events as $key => $event) {
            $N2w8F = (bool)method_exists($this->callback_class, $event);
            $N2wvPbN8I = 4 + 1;
            if (is_array($N2wvPbN8I)) goto N2weWjgx8;
            unset($N2wtIvPbN8G);
            $N2wIfQU = "zS";
            $N2wbN8H = strlen($N2wIfQU) == 1;
            if ($N2wbN8H) goto N2weWjgx8;
            if ($N2w8F) goto N2weWjgx8;
            goto N2wldMhx8;
            N2weWjgx8:
            if (isset($config[0])) goto N2weWjgxa;
            goto N2wldMhxa;
            N2weWjgxa:
            goto N2wMrKh26B;
            if (is_array($rules)) goto N2weWjgxc;
            goto N2wldMhxc;
            N2weWjgxc:
            Route::import($rules);
            goto N2wxb;
            N2wldMhxc:N2wxb:N2wMrKh26B:
            goto N2wx9;
            N2wldMhxa:
            goto N2wMrKh26D;
            $N2wM8J = $path . EXT;
            if (is_file($N2wM8J)) goto N2weWjgxe;
            goto N2wldMhxe;
            N2weWjgxe:
            $N2wM8K = $path . EXT;
            $N2wM8L = include $N2wM8K;
            goto N2wxd;
            N2wldMhxe:N2wxd:N2wMrKh26D:N2wx9:
            unset($N2wtI8E);
            $N2wtI8E = [$this->callback_class, $event];
            unset($N2wtI8M);
            $N2wtI8M = $N2wtI8E;
            $this->worker->$event = $N2wtI8M;
            $N2w8F = (bool)$N2wtI8E;
            goto N2wx7;
            N2wldMhx8:N2wx7:
        }
    }
}

?>