<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-05
 * Time: 09:22
 */

namespace App\Infrastructure\History\ParamConverter;

use App\Domain\Model\History\HallOfFameEntry;
use App\Domain\Model\History\HallOfFameRepository;
use App\Infrastructure\ParamConverter\LoaderParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class HallOfFameEntryParamConverter
 *
 * @package App\Infrastructure\History\ParamConverter
 */
class HallOfFameEntryParamConverter extends LoaderParamConverter
{
    /**
     * @var HallOfFameRepository
     */
    private $hallOfFameRepository;

    /**
     * @param HallOfFameRepository $hallOfFameRepository
     */
    public function __construct(HallOfFameRepository $hallOfFameRepository)
    {
        $this->hallOfFameRepository = $hallOfFameRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return $configuration->getClass() === HallOfFameEntry::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function loadObject($value, Request $request, ParamConverter $configuration): ?object
    {
        return $this->hallOfFameRepository->findById($value);
    }
}
