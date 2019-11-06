<?php

use BrizyDeploy\Modal\AppRepository;
use BrizyDeploy\Modal\App;
use BrizyDeploy\Modal\DeployRepository;
use BrizyDeploy\Modal\Deploy;
use BrizyDeploy\Modal\UpdateRepository;
use BrizyDeploy\Modal\Update;

class Kernel
{
    static public function init()
    {
        $appRepository = new AppRepository();
        $appRepository->create(App::getInstance());

        $deployRepository = new DeployRepository();
        $deployRepository->create(Deploy::getInstance());

        $updateRepository = new UpdateRepository();
        $updateRepository->create(Update::getInstance());

        mkdir(__DIR__ . '/../cache', 0755);
    }

    static public function isInstalled()
    {
        return (
            file_exists(__DIR__ . '/../var/app') &&
            file_exists(__DIR__ . '/../var/update') &&
            file_exists(__DIR__ . '/../var/deploy')
        );
    }
}