<?php
/**
 * Copyright (c) 2020 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Infrastructure\Http;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

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
        return new JsonResponse($params, Response::HTTP_CREATED, ['Location' => $url]);
    }

    /**
     * @return Response
     */
    public static function noContent(): Response
    {
        return new Response('', Response::HTTP_NO_CONTENT);
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
        return new JsonResponse(
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
     * @param ConstraintViolationListInterface $errors
     * @param int                              $code
     * @return Response
     */
    public static function validationFailed(ConstraintViolationListInterface $errors, int $code = 0): Response
    {
        $validationErrors = [];
        foreach ($errors as $error) {
            /** @var ConstraintViolationInterface $error */
            $validationErrors[] = [
                'message' => $error->getMessage(),
                'path'    => $error->getPropertyPath(),
                'key'     => strtolower(preg_replace('/[A-Z]/', '_$0', $error->getPropertyPath())),
                'code'    => $error->getCode(),
            ];
        }
        return self::errorCode(
            $code,
            [
                'validation' => [
                    'failed' => true,
                    'errors' => $validationErrors,
                ],
            ],
            Response::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    /**
     */
    private function __construct()
    {
        // static factory
    }
}
