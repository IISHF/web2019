<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 10:30
 */

namespace App\Application\NationalGoverningBody\Command;

use App\Domain\Common\Urlizer;
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

    /**
     * @param string      $name
     * @param string|null $id
     * @return string
     */
    protected function findSuitableSlug(string $name, ?string $id): string
    {
        return Urlizer::urlizeUnique(
            $name,
            function (string $slug) use ($id) {
                return ($tryNgb = $this->repository->findBySlug($slug)) !== null && $tryNgb->getId() !== $id;
            }
        );
    }
}
