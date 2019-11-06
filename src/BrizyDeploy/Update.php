<?php

namespace BrizyDeploy;

class Update extends BaseDeploy
{
    /**
     * Update constructor.
     * @param $zip_url
     */
    public function __construct($zip_url)
    {
        parent::__construct($zip_url);
    }

    public function execute()
    {
        return $this->innerExecute();
    }

    /**
     * @param string $name
     * @return string
     */
    protected function getNormalizedName($name)
    {
        return sys_get_temp_dir() . '/script_latest/' . str_replace('brizy/', '', $name);
    }

    /**
     * @return string
     */
    protected function generateZipName()
    {
        return sys_get_temp_dir() . '/brizy-script-' . time() . '.zip';
    }

    /**
     * @return bool
     */
    protected function backup()
    {
        return copyDirectory(realpath(__DIR__ . '/../../'), sys_get_temp_dir() . '/script_backup');
    }
}