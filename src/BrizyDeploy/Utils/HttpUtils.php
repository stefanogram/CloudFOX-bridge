<?php

namespace BrizyDeploy\Utils;

use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Request;

class HttpUtils
{
    static public function getBaseUrl(Request $request, $route_from, $route_to)
    {
        $baseUrl = $request->getScheme() . '://' . $request->getHost();
        if ($request->getPort() != 80 && $request->getPort() != 443) {
            $baseUrl .= ':' . $request->getPort();
        }

        $prefix = str_replace($route_from, '', $request->getBaseUrl());
        if ($prefix != '') {
            $baseUrl = $baseUrl . $prefix . $route_to;
        } else {
            $baseUrl = $baseUrl . $route_to;
        }

        return $baseUrl;
    }

    static public function getHttpClient()
    {
        return new Client([
            'defaults' => [
                'exceptions' => false,
                'verify' => __DIR__ . '/../../../app/certificates/ca-bundle.crt'
            ]
        ]);
    }

    static public function getClientIP(Request $request)
    {
        if ($request->server->get("HTTP_X_FORWARDED_FOR")) {
            $clientIP = explode(",", $request->server->get("HTTP_X_FORWARDED_FOR"));
            $clientIP = $clientIP[0];
        } else {
            $clientIP = $request->getClientIp();
        }

        return $clientIP;
    }
}