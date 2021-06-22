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
use GuzzleHttp\Psr7\Utils;

trait HttpRequest
{
    protected $endpoint = 'https://v2.alapi.cn';

    protected $token = '';

    protected $handleStack;

    protected $api = '';

    protected $method = 'post';

    protected $param = [];

    protected $fileParam = [];

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
    public function setParam(string $name, $value, bool $is_file = false)
    {
        $this->param[] = ['name' => $name, 'value' => $value, 'is_file' => $is_file];

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
        if (!$this->api) {
            throw new ALAPIException('API 不能为空');
        }

        $method = $this->method;
        $options = [];
        if ('get' === $method) {
            $query = [];
            foreach ($this->param as $key => $item) {
                $query[$item['name']] = $item['value'];
            }
            $options['query'] = $query;
        } else {
            $multipart = [];
            foreach ($this->param as $key => $item) {
                $multipart[$key]['name'] = $item['name'];
                if ($item['is_file']) {
                    $multipart[$key]['contents'] = Utils::tryFopen($item['value'], 'r');
                } else {
                    $multipart[$key]['contents'] = $item['value'];
                }
            }
            $options['multipart'] = $multipart;
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
