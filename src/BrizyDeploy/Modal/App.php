<?php

namespace BrizyDeploy\Modal;

class App
{
    /**
     * @var boolean
     */
    protected $installed;

    /**
     * @var string
     */
    protected $deploy_url;

    /**
     * @var string
     */
    protected $app_id;

    /**
     * @var string
     */
    protected $base_url;

    static public function getInstance()
    {
        $app = new App();
        $app->setInstalled(false);

        $config_dist = __DIR__ . '/../../../app/config/config.json.dist';
        $config_dist = json_decode(file_get_contents($config_dist), true);
        if (!$config_dist) {
            $app
                ->setAppId('')
                ->setDeployUrl('');
        } else {
            $app
                ->setAppId($config_dist['app_id'])
                ->setDeployUrl($config_dist['deploy_url']);
        }

        return $app;
    }

    /**
     * @return boolean
     */
    public function getInstalled()
    {
        return $this->installed;
    }

    /**
     * @param $installed
     * @return $this
     */
    public function setInstalled($installed)
    {
        $this->installed = $installed;

        return $this;
    }

    /**
     * @return string
     */
    public function getDeployUrl()
    {
        return $this->deploy_url;
    }

    /**
     * @param $deploy_url
     * @return $this
     */
    public function setDeployUrl($deploy_url)
    {
        $this->deploy_url = $deploy_url;

        return $this;
    }

    /**
     * @return string
     */
    public function getAppId()
    {
        return $this->app_id;
    }

    /**
     * @param $app_id
     * @return $this
     */
    public function setAppId($app_id)
    {
        $this->app_id = $app_id;

        return $this;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->base_url;
    }

    /**
     * @param $base_url
     * @return $this
     */
    public function setBaseUrl($base_url)
    {
        $this->base_url = $base_url;

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
            $this->installed,
            $this->deploy_url,
            $this->app_id,
            $this->base_url
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
            $this->installed,
            $this->deploy_url,
            $this->app_id,
            $this->base_url
            ) = unserialize($serialized);
    }
}