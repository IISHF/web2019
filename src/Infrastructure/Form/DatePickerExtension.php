<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-02
 * Time: 11:06
 */

namespace App\Infrastructure\Form;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DatePickerExtension
 *
 * @package App\Infrastructure\Form
 */
class DatePickerExtension extends AbstractTypeExtension
{
    /**
     * @var string
     */
    private $locale;

    /**
     * @param string $locale
     */
    public function __construct($locale)
    {
        $this->locale = $locale;
    }

    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes(): iterable
    {
        return [DateType::class, TimeType::class, DateTimeType::class];
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'enable_datepicker'  => false,
                'datepicker_options' => [],
                'widget'             => static function (Options $options, $previousValue) {
                    return $options['enable_datepicker'] ? 'single_text' : $previousValue;
                },
                'html5'              => static function (Options $options, $previousValue) {
                    return $options['enable_datepicker'] ? false : $previousValue;
                },
            ]
        );
        $resolver->setAllowedTypes('enable_datepicker', 'bool');
        $resolver->setAllowedTypes('datepicker_options', 'array');
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if (!$options['enable_datepicker']) {
            return;
        }

        $format = false;
        if (isset($options['format'])) {
            $conversion = [
                'MMMM'   => 'MMMM',
                'MMM'    => 'MMM',
                'MM'     => 'MM',
                'M'      => 'M',
                'Q'      => 'Q',
                'dd'     => 'DD',
                'd'      => 'D',
                'D'      => 'DDD',
                'EEEEEE' => 'dd',
                'EEEE'   => 'dddd',
                'EEE'    => 'ddd',
                'ww'     => 'w',
                'yyyy'   => 'YYYY',
                'yy'     => 'YY',
                'a'      => 'a',
                'hh'     => 'hh',
                'h'      => 'h',
                'HH'     => 'HH',
                'H'      => 'H',
                'kk'     => 'kk',
                'k'      => 'k',
                'mm'     => 'mm',
                'm'      => 'm',
                'ss'     => 'ss',
                's'      => 's',
            ];
            $format     = strtr($options['format'], $conversion);
        } elseif (isset($options['with_minutes'])) {
            $format = 'HH';
            if ($options['with_minutes'] === true) {
                $format .= ':mm';
            }
            if (($options['with_seconds'] ?? false) === true) {
                $format .= ':ss';
            }
        }

        $view->vars['enable_datepicker']  = true;
        $view->vars['datepicker_options'] = array_merge_recursive(
            [
                'locale'  => $this->locale,
                'format'  => $format,
                'buttons' => [
                    'showClear' => !$options['required'],
                ],
            ],
            $options['datepicker_options']
        );
    }
}
