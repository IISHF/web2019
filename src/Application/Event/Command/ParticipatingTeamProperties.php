<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 09:18
 */

namespace App\Application\Event\Command;

use App\Application\Common\ContactPerson;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait ParticipatingTeamProperties
 *
 * @package App\Application\Event\Command
 */
trait ParticipatingTeamProperties
{
    /**
     * @Assert\Type("string")
     * @Assert\Length(max=64)
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $name = '';

    /**
     * @Assert\Type("App\Application\Common\ContactPerson")
     * @Assert\NotNull()
     * @Assert\Valid()
     *
     * @var ContactPerson
     */
    private $contact;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return ContactPerson
     */
    public function getContact(): ContactPerson
    {
        return $this->contact;
    }
}
