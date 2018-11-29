<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 10:30
 */

namespace App\Application\NationalGoverningBody\Command;

use App\Domain\Model\NationalGoverningBody\NationalGoverningBodyRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class NationalGoverningBodyCommandHandler
 *
 * @package App\Application\NationalGoverningBody\Command
 */
abstract class NationalGoverningBodyCommandHandler implements MessageHandlerInterface
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
