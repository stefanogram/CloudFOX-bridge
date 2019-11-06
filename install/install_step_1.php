<?php

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use BrizyDeploy\Utils\HttpUtils;
use Symfony\Component\HttpFoundation\Response;
use BrizyDeploy\Modal\AppRepository;

$composerAutoload = __DIR__ . '/../vendor/autoload.php';
if (!file_exists($composerAutoload)) {
    echo "The 'vendor' folder is missing. You must run 'composer update' to resolve application dependencies.\nPlease see the README for more information.\n";
    exit;
}

require $composerAutoload;

$request = Request::createFromGlobals();

require_once __DIR__ . '/../app/Kernel.php';

if (Kernel::isInstalled()) {
    $response = new RedirectResponse(HttpUtils::getBaseUrl(
        $request,
        '/install/install_step_1.php',
        ''
    ));
    $response->send();
    exit;
}

Kernel::init();

$appRepository = new AppRepository();
$app = $appRepository->get();

$clientIP = HttpUtils::getClientIP($request);
$is_localhost = 0;
if ($clientIP == '127.0.0.1' || $clientIP == '::1') {
    $is_localhost = 1;
}

#test two-sided connection with remote server
$client = HttpUtils::getHttpClient();
$connectUrl = HttpUtils::getBaseUrl($request, '/install/install_step_1.php', '/connect.php');
$baseUrl = HttpUtils::getBaseUrl($request, '/install/install_step_1.php', '');
$url = $app->getDeployUrl() . '/export/check-connection';
$response = $client->post($url, [
    'body' => [
        'base_url' => $baseUrl,
        'connect_url' => $connectUrl,
        'project_uid' => $app->getAppId(),
        'is_localhost' => $is_localhost
    ]
]);

if ($response->getStatusCode() != 200) {
    $response = new Response('Connection error: ' . $response->getBody()->getContents());
    $response->send();
    exit;
}

$app->setBaseUrl(HttpUtils::getBaseUrl(
    $request,
    '/install/install_step_1.php',
    ''
));
$app->setInstalled(true);
$appRepository->update($app);

$response = new RedirectResponse(HttpUtils::getBaseUrl(
    $request,
    '/install/install_step_1.php',
    ''
));
$response->send();

exit;