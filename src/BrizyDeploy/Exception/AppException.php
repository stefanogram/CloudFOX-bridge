<?php

namespace BrizyDeploy\Exception;

class AppException extends \Exception
{
    /**
     * AppException constructor.
     * @param null $message
     * @param int $code
     */
    public function __construct($message = null, $code = 0)
    {
        parent::__construct($message, $code);
    }
}