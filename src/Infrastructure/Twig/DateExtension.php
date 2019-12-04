<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 11:07
 */

namespace App\Infrastructure\Twig;

use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * Class DateExtension
 *
 * @package App\Infrastructure\Twig
 */
class DateExtension extends AbstractExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('relative_time', [$this, 'formatRelativeTime'], ['is_safe' => ['html']]),
            new TwigFilter('time_until', [$this, 'formatTimeUntil'], ['is_safe' => ['html']]),
            new TwigFilter('time_ago', [$this, 'formatTimeAgo'], ['is_safe' => ['html']]),
            new TwigFilter(
                'local_time', [$this, 'formatLocalTime'], ['is_safe' => ['html'], 'needs_environment' => true]
            ),
        ];
    }

    /**
     * @param \DateTimeInterface $dateTime
     * @return string[]
     */
    private function createTimeStrings(\DateTimeInterface $dateTime): array
    {
        $isoDate      = $dateTime->format(\DateTimeInterface::W3C);
        $readableDate = $dateTime->format('F j, Y H:i');
        return [$isoDate, $readableDate];
    }

    /**
     * @param \DateTimeInterface $dateTime
     * @return string
     */
    public function formatRelativeTime(\DateTimeInterface $dateTime): string
    {
        [$isoDate, $readableDate] = $this->createTimeStrings($dateTime);
        return <<<HTML
<relative-time datetime="$isoDate" data-toggle="tooltip" data-placement="top">$readableDate</relative-time>
HTML;
    }

    /**
     * @param \DateTimeInterface $dateTime
     * @return string
     */
    public function formatTimeUntil(\DateTimeInterface $dateTime): string
    {
        [$isoDate, $readableDate] = $this->createTimeStrings($dateTime);
        return <<<HTML
<time-until datetime="$isoDate" data-toggle="tooltip" data-placement="top">$readableDate</time-until>
HTML;
    }

    /**
     * @param \DateTimeInterface $dateTime
     * @param bool               $micro
     * @return string
     */
    public function formatTimeAgo(\DateTimeInterface $dateTime, bool $micro = false): string
    {
        [$isoDate, $readableDate] = $this->createTimeStrings($dateTime);
        $microAttr = $micro ? 'format="micro"' : '';
        return <<<HTML
<time-ago datetime="$isoDate" $microAttr data-toggle="tooltip" data-placement="top">$readableDate</time-ago>
HTML;
    }

    /**
     * @param Environment        $env
     * @param \DateTimeInterface $dateTime
     * @param array              $options
     * @return string
     */
    public function formatLocalTime(Environment $env, \DateTimeInterface $dateTime, array $options = []): string
    {
        $defaultOptions = [
            'year'           => 'numeric',
            'month'          => 'long',
            'day'            => 'numeric',
            'weekday'        => null,
            'hour'           => 'numeric',
            'minute'         => 'numeric',
            'second'         => null,
            'time-zone-name' => null,
        ];
        $options        = array_intersect_key($options, $defaultOptions);
        $optionsAttrs   = [];
        foreach (array_replace($defaultOptions, $options) as $k => $v) {
            if ($v !== null) {
                $optionsAttrs[] = \twig_escape_filter($env, $k)
                    . '="'
                    . \twig_escape_filter($env, $v, 'html_attr')
                    . '"';
            }
        }
        $optionsAttr = implode(' ', $optionsAttrs);

        [$isoDate, $readableDate] = $this->createTimeStrings($dateTime);
        return <<<HTML
<local-time datetime="$isoDate" $optionsAttr data-toggle="tooltip" data-placement="top">$readableDate</local-time>
HTML;
    }
}
