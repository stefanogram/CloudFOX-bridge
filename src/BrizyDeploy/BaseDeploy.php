<?php

namespace BrizyDeploy;

use BrizyDeploy\Utils\HttpUtils;
use GuzzleHttp\Stream\Stream;

abstract class BaseDeploy
{
    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @var string
     */
    protected $zip_url;

    public function __construct($zip_url)
    {
        ini_set('max_execution_time', 120);
        ini_set('memory_limit', '256M');

        $this->zip_url = $zip_url;
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    protected function innerExecute()
    {
        if (!$this->backup()) {
            $this->errors['error']['backup'][] = 'backup was not created';
            return false;
        }

        try {
            return $this->extract();
        } catch (\Exception $e) {
            $this->errors['error']['extract'][] = $e->getMessage();
            return false;
        }
    }

    /**
     * @return bool
     */
    abstract protected function backup();

    /**
     * @return bool
     */
    protected function extract()
    {
        $zip = zip_open($this->getZipPath());
        if (!is_resource($zip)) {
            $this->errors['error']['zip'][] = 'Invalid zip';
            return false;
        }

        $result = true;
        while ($zip_entry = zip_read($zip)) {
            $name = zip_entry_name($zip_entry);
            if (!preg_match("/\/$/", $name)) {
                $content = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
                $name = $this->getNormalizedName($name);

                $dirname = dirname($name);
                if (!is_dir($dirname)) {
                    mkdir($dirname, 0755, true);
                }

                if (file_exists($name) && !is_writable($name)) {
                    $this->errors['error']['permissions'][] = $name;
                    $result = false;
                }

                $bytes = file_put_contents($name, $content);
                if ($bytes === false) {
                    $this->errors['error']['files'][] = $name;
                    $result = false;
                }
            }
        }

        zip_close($zip);

        return $result;
    }

    /**
     * @param string $name
     * @return string
     */
    abstract protected function getNormalizedName($name);

    abstract protected function generateZipName();

    /**
     * @return string|null
     */
    protected function getZipPath()
    {
        $zip_name = $this->generateZipName();
        $this->postExecuteRemove($zip_name);
        $resource = fopen($zip_name, 'w');
        if ($resource === false) {
            $this->errors['error']['zip'][] = 'Can\'t create ' . $zip_name;
            return null;
        }

        $stream = Stream::factory($resource);
        $client = HttpUtils::getHttpClient();

        $response = $client->get(
            $this->zip_url,
            ['save_to' => $stream]
        );
        if ($response->getStatusCode() != 200) {
            $this->errors['error']['zip'][] = 'Zip was not downloaded';
            return null;
        }

        return $zip_name;
    }

    /**
     * @param $zip_name
     */
    protected function postExecuteRemove($zip_name)
    {
        register_shutdown_function(function ($zip_name) {
            unlink($zip_name);
        }, $zip_name);
    }
}