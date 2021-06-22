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
       ->setParam('type','tcn') # 
       ->throw() # 如果返回 code 不等于 200 则抛异常，不调用这个方法则不会抛异常
       ->request();  # 请求

var_dump($result); 
var_dump($result->getData()); # 获取请求数据
```
```php
# 添加文件上传
use ALAPI\Client;

$client = new Client('你的token');

# 开始请求
$result = $client->setApi('/api/image')
       ->setParam('image','文件路径',true) # 第二个参数是文件路径，第三个参数是否为文件
       ->setParam('type','alapi') # 
       ->throw() # 如果返回 code 不等于 200 则抛异常，不调用这个方法则不会抛异常
       ->request();  # 请求

var_dump($result); 
var_dump($result->getData()); # 获取请求数据

```

## 接口文档地址

ALAPI 接口文档地址：[https://www.alapi.cn](https://www.alapi.cn)