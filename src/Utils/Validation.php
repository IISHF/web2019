<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-04
 * Time: 10:19
 */

namespace App\Utils;

use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Validator\ConstraintViolationInterface;

/**
 * Class Validation
 *
 * @package App\Utils
 */
final class Validation
{
    /**
     */
    private function __construct()
    {
    }

    /**
     * @param ValidationFailedException $e
     * @return string[]
     */
    public static function getViolations(ValidationFailedException $e): array
    {
        return array_map(
            function (ConstraintViolationInterface $violation) {
                return $violation->getPropertyPath() . ': ' . $violation->getMessage();
            },
            iterator_to_array($e->getViolations())
        );
    }
}
