<?php
class Service
{
    public static $errorMsg = "";
    
    /**
     * 获得最后一次的错误信息
     * @return string 错误信息
     */
    public function getLastErrMsg()
    {
        return self::$errorMsg;
    }
}