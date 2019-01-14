<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 19:55
 */

namespace App\Infrastructure\Event\ParamConverter;

use App\Domain\Model\Event\Event;
use App\Domain\Model\Event\EventRepository;
use App\Infrastructure\ParamConverter\LoaderParamConverter;
use Ramsey\Uuid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Class EventParamConverter
 *
 * @package App\Infrastructure\Event\ParamConverter
 */
class EventParamConverter extends LoaderParamConverter
{
    /**
     * @var EventRepository
     */
    private $eventRepository;

    /**
     * @param EventRepository $eventRepository
     */
    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return $configuration->getClass() === Event::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function loadObject($value, Request $request, ParamConverter $configuration): ?object
    {
        if (Uuid::isValid($value)) {
            return $this->eventRepository->findById($value);
        }

        $seasonParam = $configuration->getOptions()['season'] ?? 'season';
        if (!$request->attributes->has($seasonParam)) {
            if ($configuration->isOptional()) {
                return null;
            }
            throw new BadRequestHttpException(
                sprintf(
                    'Season parameter %s not found for class %s.',
                    $seasonParam,
                    $configuration->getClass()
                )
            );
        }
        $seasonValue = $request->attributes->get($seasonParam);
        if (!$seasonValue) {
            if ($configuration->isOptional()) {
                return null;
            }
            throw new BadRequestHttpException(
                sprintf(
                    'Value not found for season parameter %s for class %s.',
                    $seasonParam,
                    $configuration->getClass()
                )
            );
        }

        $event = $this->eventRepository->findBySlug($seasonValue, $value);
        if (!$event && !$configuration->isOptional()) {
            throw new NotFoundHttpException(
                sprintf(
                    'Event not found for event parameter %s with value %s and season parameter %s with value %s.',
                    $configuration->getName(),
                    $value,
                    $seasonParam,
                    $seasonValue
                )
            );
        }
        return $event;
    }
}
