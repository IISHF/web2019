<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-17
 * Time: 07:15
 */

namespace App\Infrastructure\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * Class PagingRequest
 *
 * @package App\Infrastructure\Controller
 */
class PagingRequest
{
    private const DEFAULT_PAGE  = 1;
    private const DEFAULT_LIMIT = 30;

    /**
     * @var Request
     */
    private $request;

    /**
     * @param Request $request
     * @return self
     */
    public static function create(Request $request): self
    {
        return new self($request);
    }

    /**
     * @param Request $request
     */
    private function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param int $defaultPage
     * @return int
     */
    public function getPage(int $defaultPage = self::DEFAULT_PAGE): int
    {
        return $this->request->query->getInt('page', $defaultPage);
    }

    /**
     * @param int $defaultLimit
     * @return int
     */
    public function getLimit(int $defaultLimit = self::DEFAULT_LIMIT): int
    {
        return $this->request->query->getInt('limit', $defaultLimit);
    }
}
