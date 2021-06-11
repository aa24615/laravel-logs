<?php

namespace Zyan\LaravelLogs;

use Illuminate\Support\Facades\Storage;

class Logs
{
    protected $content = '';

    public function __construct()
    {
        $this->request = request();
    }

    public function request()
    {

        $start = str_pad(LARAVEL_START, 15, 0);
        $end = str_pad(microtime(true), 15, 0);

        $standard = config('develop.runtime-lower-limit');
        $time = ($end - LARAVEL_START) * 1000;

        $content = "请求地址[{$this->request->getUri()}] 开始时间[{$start}] 结束时间[{$end}] 运行时间[{$time}]";

        $this->appendContent($content);

        $content = print_r($this->request->all(), true);

        $this->appendContent($content);

        $disk = $this->getDisk();

        $this->write($disk);
    }


    protected function sql()
    {
    }

    public function response()
    {
    }


    /**
     * getDisk.
     *
     * @return \Illuminate\Contracts\Filesystem\Filesystem|Storage
     *
     * @author 读心印 <aa24615@qq.com>
     */
    protected function getDisk()
    {
        return Storage::disk('storage');
    }

    /**
     * write.
     *
     * @param Storage $disk
     * @param string $content
     *
     * @return void
     *
     * @author 读心印 <aa24615@qq.com>
     */
    protected function write($disk)
    {
        $content = $this->br().$this->content;

        if ($disk->exists(config('logs.path')) === false) {
            $disk->put(config('logs.path'), $content);
        } else {
            $disk->append(config('logs.path'), $content);
        }

        $this->content = '';
    }

    protected function appendContent($content)
    {
        $this->content = $this->content.PHP_EOL.$content;
    }

    protected function br()
    {
        return PHP_EOL.'---------------------------------------------------------------'.PHP_EOL;
    }
}
