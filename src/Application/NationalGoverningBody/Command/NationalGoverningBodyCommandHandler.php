<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 10:30
 */

namespace App\Application\NationalGoverningBody\Command;

use App\Domain\Common\Urlizer;
use App\Domain\Model\NationalGoverningBody\NationalGoverningBody;
use App\Domain\Model\NationalGoverningBody\NationalGoverningBodyRepository;

/**
 * Class NationalGoverningBodyCommandHandler
 *
 * @package App\Application\NationalGoverningBody\Command
 */
abstract class NationalGoverningBodyCommandHandler
{
    /**
     * @var NationalGoverningBodyRepository
     */
    protected $ngbRepository;

    /**
     * @param NationalGoverningBodyRepository $ngbRepository
     */
    public function __construct(NationalGoverningBodyRepository $ngbRepository)
    {
        $this->ngbRepository = $ngbRepository;
    }

    /**
     * @param string $id
     * @return NationalGoverningBody
     */
    protected function getNationalGoverningBody(string $id): NationalGoverningBody
    {
        $ngb = $this->ngbRepository->findById($id);
        if (!$ngb) {
            throw new \OutOfBoundsException('No national governing body found for id ' . $id);
        }
        return $ngb;
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
                return ($tryNgb = $this->ngbRepository->findBySlug($slug)) !== null && $tryNgb->getId() !== $id;
            }
        );
    }
}
