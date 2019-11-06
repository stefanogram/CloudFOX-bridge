<?php

use BrizyDeploy\Modal\AppRepository;
use BrizyDeploy\Modal\DeployRepository;
use BrizyDeploy\Modal\Update;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BrizyDeploy\Modal\UpdateRepository;

require_once __DIR__ . '/app/BrizyDeployRequirements.php';

$composerAutoload = __DIR__ . '/vendor/autoload.php';
if (!file_exists($composerAutoload)) {
    echo "The 'vendor' folder is missing. You must run 'composer update' to resolve application dependencies.\nPlease see the README for more information.\n";
    exit;
}

require $composerAutoload;

require_once __DIR__ . '/app/Kernel.php';

$request = Request::createFromGlobals();

$appRepository = new AppRepository();
$app = $appRepository->get();
if (!$app || $request->get('app_id') != $app->getAppId()) {
    $response = new Response('Unauthorized', 401);
    $response->send();
    exit;
}

$action = $request->get('action');
switch ($action) {
    case 'maintenance':
        $updateRepository = new UpdateRepository();
        $update = $updateRepository->get();
        if (!$update) {
            $response = new Response('Error.', 400);
            $response->send();
            exit;
        }

        $update->setMaintenance(true);
        $updateRepository->update($update);

        break;
    case 'update':
        $updateRepository = new UpdateRepository();
        $updateRepository->create(Update::getInstance());

        break;
    case 'deploy':
        $deployRepository = new DeployRepository();
        $deploy = $deployRepository->get();
        if (!$deploy) {
            $response = new Response('Error.', 400);
            $response->send();
            exit;
        }

        $deploy->setExecute(true);
        $deployRepository->update($deploy);

        break;
    default:
        $response = new Response('Undefined action', 404);
        $response->send();
        exit;
}

$response = new Response('Done.', 200);
$response->send();
exit;