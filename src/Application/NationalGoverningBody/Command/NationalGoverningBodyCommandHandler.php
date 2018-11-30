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
     * @param string      $acronym
     * @param string|null $id
     * @return string
     */
    protected function findSuitableSlug(string $name, string $acronym, ?string $id): string
    {
        $baseSlug = Urlizer::urlize($name . '-' . $acronym);
        $slug     = $baseSlug;
        $i        = 1;
        while (($tryNgb = $this->repository->findBySlug($slug)) !== null && $tryNgb->getId() !== $id) {
            $slug = $baseSlug . '-' . $i;
            $i++;
        }
        return $slug;
    }
}
