<?php

namespace BrizyDeploy\Modal;

class Deploy
{
    /**
     * @var boolean
     */
    protected $execute;

    /**
     * @var int
     */
    protected $timestamp;

    public function __construct($execute)
    {
        $this->execute = $execute;
    }

    static public function getInstance()
    {
        return new Deploy(true);
    }

    /**
     * @return boolean
     */
    public function getExecute()
    {
        return $this->execute;
    }

    /**
     * @param $execute
     * @return $this
     */
    public function setExecute($execute)
    {
        $this->execute = $execute;

        return $this;
    }

    /**
     * @return int
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param $timestamp
     * @return $this
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * String representation of object
     * @link https://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize([
            $this->execute
        ]);
    }

    /**
     * Constructs the object
     * @link https://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        list(
            $this->execute
            ) = unserialize($serialized);
    }
}