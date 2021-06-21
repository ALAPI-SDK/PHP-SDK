<?php

/*
 * This file is part of the ALAPI-SDK/php-sdk.
 * (c) Alone88 <im@alone88.cn>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace ALAPI\Traits;

use ALAPI\Exception\ALAPIException;
use ALAPI\Model\Result;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\HandlerStack;

trait HttpRequest
{
    protected $endpoint = 'https://v2.alapi.cn';

    protected $token = '';

    protected $handleStack;

    protected $api = '';

    protected $method = 'post';

    protected $param = [];

    protected $throw = false;

    /**
     * Client constructor.
     *
     * @param $handleStack
     */
    public function __construct(string $token, ?callable $handleStack = null)
    {
        $this->token = $token;
        $this->handleStack = HandlerStack::create($handleStack);
    }

    public function setApi(string $api)
    {
        $this->api = $api;

        return $this;
    }

    /**
     * @return $this
     */
    public function setMethod(string $method)
    {
        $method = strtolower($method);
        if (!in_array($method, ['get', 'post', 'put'])) {
            throw new ALAPIException("not support {$method}  method");
        }
        $this->method = $method;

        return $this;
    }

    /**
     * @param $value
     *
     * @return HttpRequest
     */
    public function setParam(string $name, $value)
    {
        $this->param[$name] = $value;

        return $this;
    }

    public function setParams(array $param)
    {
        $params = array_merge($param, $this->param);

        $this->param = $params;

        return $this;
    }

    /**
     * @return HttpRequest
     */
    public function setToken(string $token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return HttpRequest
     */
    public function setHandleStack(?callable $handleStack)
    {
        $this->handleStack = $handleStack;

        return $this;
    }

    /**
     * @return $this 请求出错抛出异常
     */
    public function throw()
    {
        $this->throw = true;

        return $this;
    }

    public function request(): Result
    {
        $client = $this->createHttpClient();
        $options = [];
        if ('get' == $this->method) {
            $options['query'] = $this->param;
        } else {
            $options['form_params'] = $this->param;
        }

        try {
            $response = $client->request($this->method, $this->api, $options);
            if (200 !== $response->getStatusCode()) {
                throw new ALAPIException("ALAPI 请求失败了,状态码: {$response->getStatusCode()} ");
            }
            $body = (string) $response->getBody();
            $data = json_decode($body, true);
            if ($this->throw && 200 !== $data['code']) {
                throw new ALAPIException($data['msg']);
            }

            return new Result($data['code'], $data['msg'], $data['log_id'], $data['data'], $body);
        } catch (GuzzleException $e) {
            throw new ALAPIException($e);
        }
    }

    protected function createHttpClient(): Client
    {
        return new Client([
            'base_uri' => $this->endpoint,
            'handle' => $this->handleStack,
            'headers' => [
                'token' => $this->token,
                'User-Agent' => 'ALAPI-PHP/1.0',
            ],
        ]);
    }
}
