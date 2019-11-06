<?php

use BrizyDeploy\Modal\AppRepository;
use BrizyDeploy\Utils\HttpUtils;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use BrizyDeploy\Update;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

require_once __DIR__ . '/app/BrizyDeployRequirements.php';
require_once 'app/utils.php';

$composerAutoload = __DIR__ . '/vendor/autoload.php';
if (!file_exists($composerAutoload)) {
    echo "The 'vendor' folder is missing. You must run 'composer update' to resolve application dependencies.\nPlease see the README for more information.\n";
    exit;
}

require $composerAutoload;

$brizyDeployRequirements = new BrizyDeployRequirements();

$majorProblems = $brizyDeployRequirements->getFailedRequirements();
$minorProblems = $brizyDeployRequirements->getFailedRecommendations();
$hasMajorProblems = (bool) count($majorProblems);
$hasMinorProblems = (bool) count($minorProblems);

if ($hasMajorProblems || $hasMinorProblems) {
    $response = new JsonResponse($majorProblems, 400);
    $response->send();
    exit;
}

$request = Request::createFromGlobals();

$appRepository = new AppRepository();
$app = $appRepository->get();
if (!$app || $request->get('app_id') != $app->getAppId()) {
    $response = new Response('Unauthorized', 401);
    $response->send();
    exit;
}

if (!$zip_url = $request->query->get('zip_url')) {
    $response = new JsonResponse([
        'success' => false,
        'message' => 'zip_url is required'
    ], 400);
    $response->send();
    exit;
}

$update = new Update($zip_url);
$result = $update->execute();
if ($result) {
    $response = new JsonResponse('Done', 200);
} else {
    $response = new JsonResponse($update->getErrors(), 400);
}

$response->send();

exit;
