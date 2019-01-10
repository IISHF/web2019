<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-10
 * Time: 10:58
 */

namespace App\Application\Article\Command;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait ArticleAuthor
 *
 * @package App\Application\Article\Command
 */
trait ArticleAuthor
{
    /**
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     * @Assert\Email
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $author;

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }
}
