<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 11:07
 */

namespace App\Infrastructure\Twig;

/**
 * Class DateExtension
 *
 * @package App\Infrastructure\Twig
 */
class DateExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        return [
            new \Twig_SimpleFilter(
                'relative_date',
                [$this, 'formatRelativeDate'],
                ['is_safe' => ['html']]
            ),
        ];
    }

    /**
     * @param \DateTimeInterface $dateTime
     * @return string
     */
    public function formatRelativeDate(\DateTimeInterface $dateTime): string
    {
        $isoDate      = $dateTime->format(\DateTimeInterface::W3C);
        $readableDate = $dateTime->format('F j, Y H:i');
        return <<<HTML
<relative-time datetime="$isoDate" data-toggle="tooltip" data-placement="top">$readableDate</relative-time>
HTML;
    }
}
