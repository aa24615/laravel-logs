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
        $content = $this->request->ip()." ".$this->request->method()." : ".$this->request->getUri();

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
        return Storage::disk('local');
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
