<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 16:54
 */

namespace App\Application\Document\Command;

use App\Application\Document\Validator\UniqueDocumentVersion;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait DocumentVersionProperties
 *
 * @package App\Application\Document\Command
 */
trait DocumentVersionProperties
{
    /**
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     * @Assert\NotBlank()
     * @UniqueDocumentVersion()
     *
     * @var string
     */
    private $version = '';

    /**
     * @Assert\Type("\DateTimeImmutable")
     * // disabled @see https://github.com/symfony/symfony/issues/29831
     * //Assert\LessThanOrEqual(
     * //     propertyPath="validUntil",
     * //     message="This value should be less than or equal to valid until."
     * //)
     * @Assert\Expression(
     *      expression="value === null or this.getValidUntil() === null or value <= this.getValidUntil()",
     *      message="This value should be less than or equal to valid until."
     * )
     *
     * @var \DateTimeImmutable|null
     */
    private $validFrom;

    /**
     * @Assert\Type("\DateTimeImmutable")
     * // disabled @see https://github.com/symfony/symfony/issues/29831
     * //Assert\GreaterThanOrEqual(
     * //     propertyPath="validFrom",
     * //     message="This value should be greater than or equal to valid from."
     * //)
     * @Assert\Expression(
     *      expression="value === null or this.getValidFrom() === null or value >= this.getValidFrom()",
     *      message="This value should be greater than or equal to valid from."
     * )
     *
     * @var \DateTimeImmutable|null
     */
    private $validUntil;

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getValidFrom(): ?\DateTimeImmutable
    {
        return $this->validFrom;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getValidUntil(): ?\DateTimeImmutable
    {
        return $this->validUntil;
    }
}
