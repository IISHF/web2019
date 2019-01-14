<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 19:38
 */

namespace App\Infrastructure\Event\Form;

use Symfony\Component\Form\AbstractType;

/**
 * Class EuropeanChampionshipType
 *
 * @package App\Infrastructure\Event\Form
 */
class EuropeanChampionshipType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function getParent(): ?string
    {
        return TitleEventType::class;
    }
}
