<?php

namespace Zyan\LaravelLogs;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class Logs
{
    /**
     * @var string
     */
    protected $content = '';

    public function __construct()
    {
        $this->appendContent($this->br());
        $this->request = request();
    }

    /**
     * request.
     *
     * @return self
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function request()
    {
        $content = $this->request->ip()." ".$this->request->method()." : ".$this->request->getUri();
        $this->appendContent($content);

        $content = 'userAgent:'.$this->request->getRequestFormat();
        $this->appendContent($content);

        $content = 'referer:'.(isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '');
        $this->appendContent($content);

        $content = "ALL DATA:".print_r($this->request->all(), true);
        $this->appendContent($content);

        $content = "POST DATA:".print_r($this->request->post(), true);
        $this->appendContent($content);

        $content = "GET DATA:".print_r($this->request->query(), true);
        $this->appendContent($content);

        return $this;
    }

    /**
     * sql.
     *
     * @return self
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function sql()
    {
        $data = [];

        foreach (DB::getQueryLog() as $log) {
            if (!array_key_exists($log['query'], $data)) {
                $data[$log['query']] = 0;
            }
            ++$data[$log['query']];
        }
        arsort($data);
        $this->appendContent(var_export($data, true));

        return $this;
    }

    /**
     * response.
     *
     * @param Response $response
     *
     * @return self
     *
     * @author 读心印 <aa24615@qq.com>
     */
    public function response($response)
    {
        $body = $response->getContent();

        $data = json_decode($body, true);
        if ($data) {
            $this->appendContent("response: ".print_r($body, true));
        } else {
            $this->appendContent("response: ".$body);
        }

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
        $this->appendContent($this->br());
        $disk->info($this->content);
        $this->content = '';
    }

    /**
     * appendContent.
     *
     * @param string $content
     *
     * @return self
     *
     * @author 读心印 <aa24615@qq.com>
     */
    protected function appendContent($content)
    {
        $this->content = $this->content.PHP_EOL.$content;
    }

    /**
     * br.
     *
     * @return string
     *
     * @author 读心印 <aa24615@qq.com>
     */
    protected function br()
    {
        return '---------------------------------------------------------------';
    }
}
