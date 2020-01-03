<?php
/**
 * Copyright (c) 2020 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Infrastructure\Http;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Class ApiResponse
 *
 * @package App\Infrastructure\Http
 */
final class ApiResponse
{
    /**
     * @param string                $route #Route
     * @param array                 $params
     * @param UrlGeneratorInterface $urlGenerator
     * @return Response
     */
    public static function created(string $route, array $params, UrlGeneratorInterface $urlGenerator): Response
    {
        $url = $urlGenerator->generate(
            $route,
            $params,
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        return JsonResponse::create($params, Response::HTTP_CREATED, ['Location' => $url]);
    }

    /**
     * @return Response
     */
    public static function noContent(): Response
    {
        return Response::create('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param int   $code
     * @param array $payload
     * @param int   $status
     * @return Response
     */
    public static function errorCode(
        int $code = 0,
        array $payload = [],
        int $status = Response::HTTP_INTERNAL_SERVER_ERROR
    ): Response {
        return JsonResponse::create(
            array_merge(
                $payload,
                [
                    'error' => [
                        'code' => $code,
                    ],
                ]
            ),
            $status
        );
    }

    /**
     */
    private function __construct()
    {
        // static factory
    }
}
