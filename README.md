## ALAPI-SDK
这是 ALAPI 的 PHP SDK

## 环境要求
- PHP >= 7.2
- ext-json
- ext-curl

## 安装 

```composer
composer require alapi/php-sdk
```


## 使用


```php

# 初始化客户端
use ALAPI\Client;

$client = new Client('你的token');

# 开始请求
$result = $client->setApi('/api/url')
       ->setParam('url','https://www.alapi.cn') # 设置请求参数，设置多个参数可以调用多次这个方法
       ->setParam('type','tcn') # 目前不支持直接上传文件等,上传图片请使用 base64
       ->throw() # 如果返回 code 不等于 200 则抛异常，不调用这个方法则不会抛异常
       ->request();  # 请求

var_dump($result); 
var_dump($result->getData()); # 获取请求数据
```

