

# zyan/laravel-logs

一个记录laravel用户请求的日志中间件

## 要求

1. php >= 7.2
2. Composer

## 安装

```shell
composer require zyan/laravel-logs -vvv
```
## 配置

```shell
php artisan vendor:publish --provider= "Zyan\\LaravelLogs\\LogsServiceProvider" --tag=config
```
然后去 `config/logs.php` 配置写入通道

```php

return [
    'driver' => 'stack' //在 config/logging.php 配置日志通道
];


```

## 中间件

请在 `app/Http/Kernel.php` 中添加默认的中间件

```php


namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Zyan\LaravelLogs\Middleware\RequestLogs; //使用默认中间件


class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        RequestLogs::class, //全局使用
        //...
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            RequestLogs::class, //在web中使用
            //...
        ],

        'api' => [
            RequestLogs::class, //在api中使用
            //...
        ],
    ];
    //...
}
```

## 自定义

自已手动添加一个中间件

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;
use Zyan\LaravelLogs\Logs;

class RequestLogs
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);
    
        try {
            $logs = new Logs();
            $logs->request()->sql()->response($response)->write();
            //按需配置你需要记录的信息
            //->request() 记录请求信息
            //->sql() 记录sql日志
            //->response($response) 记录返回日志
            //->write() 执行写入
        }catch (\Exception $e){
            Log::error($e->getMessage().' '.$e->getFile().":".$e->getLine());
        }
    
        return $response;
    }
}

```


## 参与贡献

1. fork 当前库到你的名下
2. 在你的本地修改完成审阅过后提交到你的仓库
3. 提交 PR 并描述你的修改，等待合并

## License

[MIT license](https://opensource.org/licenses/MIT)
