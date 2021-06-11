<?php

namespace Zyan\LaravelLogs;

use Illuminate\Support\Facades\Log;

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
        $content = "DATA:".print_r($this->request->all(), true);
        $this->appendContent($content);

        return $this;
    }

    /**
     * sql.
     *
     * @return $this
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function sql()
    {
        return $this;
    }

    /**
     * response.
     *
     * @return $this
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function response()
    {
        return $this;
    }


    /**
     * getDisk.
     *
     * @return Log|\Psr\Log\LoggerInterface
     *
     * @author 读心印 <aa24615@qq.com>
     */
    protected function getDisk()
    {
        $driver = config('logs.driver');

        if (is_array($driver)) {
            $disk = Log::stack($driver);
        } else {
            $disk = Log::channel($driver);
        }
        return $disk;
    }

    /**
     * write.
     *
     * @return void
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function write()
    {
        $disk = $this->getDisk();
        $content = $this->br().$this->content;
        $disk->info($content);
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
