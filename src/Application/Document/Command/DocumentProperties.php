<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 16:53
 */

namespace App\Application\Document\Command;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait DocumentProperties
 *
 * @package App\Application\Document\Command
 */
trait DocumentProperties
{
    /**
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $title = '';

    /**
     * @Assert\Type("array")
     * @Assert\All({
     *     @Assert\Type("string"),
     *     @Assert\NotBlank()
     * })
     *
     * @var string[]
     */
    private $tags = [];

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }
}
