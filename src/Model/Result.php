<?php

/*
 * This file is part of the ALAPI-SDK/php-sdk.
 * (c) Alone88 <im@alone88.cn>
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace ALAPI\Model;

class Result
{
    /**
     * @var int 状态码
     */
    protected $code;
    /**
     * @var string 提示信息
     */
    protected $msg;
    /**
     * @var int 请求日志ID
     */
    protected $log_id;
    /**
     * @var mixed 数据
     */
    protected $data;

    /**
     * @var string 原始数据
     */
    protected $rawData;

    /**
     * Result constructor.
     *
     * @param $code
     * @param $msg
     * @param $log_id
     * @param $data
     * @param $rawData
     */
    public function __construct($code, $msg, $log_id, $data, $rawData)
    {
        $this->code = $code;
        $this->msg = $msg;
        $this->log_id = $log_id;
        $this->data = $data;
        $this->rawData = $rawData;
    }

    /**
     * @return mixed
     */
    public function getCode(): int
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getMsg(): string
    {
        return $this->msg;
    }

    /**
     * @return mixed
     */
    public function getLogId(): int
    {
        return $this->log_id;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    public function successful(): bool
    {
        return 200 === $this->code;
    }

    /**
     * @param false $pureData 是否只返回 data 数据
     *
     * @return mixed
     */
    public function toArray($pureData = false)
    {
        if ($pureData) {
            return $this->data;
        }

        return json_decode($this->rawData, true);
    }
}
