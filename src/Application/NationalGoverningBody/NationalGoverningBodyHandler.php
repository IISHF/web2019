<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 10:30
 */

namespace App\Application\NationalGoverningBody;

use App\Domain\Model\NationalGoverningBody\NationalGoverningBodyRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class NationalGoverningBodyHandler
 *
 * @package App\Application\NationalGoverningBody
 */
abstract class NationalGoverningBodyHandler implements MessageHandlerInterface
{
    /**
     * @var NationalGoverningBodyRepository
     */
    protected $repository;

    /**
     * @param NationalGoverningBodyRepository $repository
     */
    public function __construct(NationalGoverningBodyRepository $repository)
    {
        $this->repository = $repository;
    }
}
