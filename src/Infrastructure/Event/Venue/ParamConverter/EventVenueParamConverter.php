<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 20:00
 */

namespace App\Infrastructure\Event\Venue\ParamConverter;

use App\Domain\Model\Event\Venue\EventVenue;
use App\Domain\Model\Event\Venue\EventVenueRepository;
use App\Infrastructure\ParamConverter\LoaderParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class EventVenueParamConverter
 *
 * @package App\Infrastructure\Event\Venue\ParamConverter
 */
class EventVenueParamConverter extends LoaderParamConverter
{
    /**
     * @var EventVenueRepository
     */
    private $venueRepository;

    /**
     * @param EventVenueRepository $venueRepository
     */
    public function __construct(EventVenueRepository $venueRepository)
    {
        $this->venueRepository = $venueRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return $configuration->getClass() === EventVenue::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function loadObject($value, Request $request, ParamConverter $configuration): ?object
    {
        return $this->venueRepository->findById($value);
    }
}
