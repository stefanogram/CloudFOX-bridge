<?php

namespace BrizyDeploy\Exception;

class DeployExeption extends \Exception
{
    /**
     * DeployExeption constructor.
     * @param null $message
     * @param int $code
     */
    public function __construct($message = null, $code = 0)
    {
        parent::__construct($message, $code);
    }
}