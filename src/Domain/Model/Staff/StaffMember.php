<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-01
 * Time: 07:35
 */

namespace App\Domain\Model\Staff;

use App\Domain\Model\Common\CreateTracking;
use App\Domain\Model\Common\UpdateTracking;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Class StaffMember
 *
 * @package App\Domain\Model\Staff
 *
 * @ORM\Entity(repositoryClass="StaffMemberRepository")
 * @ORM\Table(name="staff_members")
 */
class StaffMember
{
    use CreateTracking, UpdateTracking;

    /**
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     *
     * @var string
     */
    private $id;

    /**
     * @ORM\Column(name="first_name", type="string", length=128)
     *
     * @var string
     */
    private $firstName;

    /**
     * @ORM\Column(name="last_name", type="string", length=128)
     *
     * @var string
     */
    private $lastName;

    /**
     * @ORM\Column(name="email", type="string", length=128)
     *
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(name="title", type="string", length=128)
     *
     * @var string
     */
    private $title;

    /**
     * @ORM\Column(name="roles", type="json")
     *
     * @var string[]
     */
    private $roles;

    /**
     * @param string   $id
     * @param string   $firstName
     * @param string   $lastName
     * @param string   $email
     * @param string   $title
     * @param string[] $roles
     * @return self
     */
    public static function create(
        string $id,
        string $firstName,
        string $lastName,
        string $email,
        string $title,
        array $roles
    ): self {
        return new self($id, $firstName, $lastName, $email, $title, $roles);
    }

    /**
     * @param string   $id
     * @param string   $firstName
     * @param string   $lastName
     * @param string   $email
     * @param string   $title
     * @param string[] $roles
     */
    private function __construct(
        string $id,
        string $firstName,
        string $lastName,
        string $email,
        string $title,
        array $roles
    ) {
        Assert::uuid($id);

        $this->id = $id;

        $this->setFirstName($firstName)
             ->setLastName($lastName)
             ->setEmail($email)
             ->setTitle($title)
             ->setRoles($roles)
             ->initCreateTracking()
             ->initUpdateTracking();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return $this
     */
    public function setFirstName(string $firstName): self
    {
        Assert::lengthBetween($firstName, 1, 128);
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return $this
     */
    public function setLastName(string $lastName): self
    {
        Assert::lengthBetween($lastName, 1, 128);
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @param bool $lastNameFirst
     * @return string
     */
    public function getName(bool $lastNameFirst = false): string
    {
        if ($lastNameFirst) {
            return $this->lastName . ', ' . $this->firstName;
        }
        return $this->firstName . ' ' . $this->lastName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): self
    {
        // @see \Symfony\Component\Validator\Constraints\EmailValidator::PATTERN_LOOSE
        Assert::regex($email, '/^.+\@\S+\.\S+$/');
        Assert::lengthBetween($email, 1, 128);
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle(string $title): self
    {
        Assert::lengthBetween($title, 1, 128);
        $this->title = $title;
        return $this;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    /**
     * @param string[] $roles
     * @return $this
     */
    public function setRoles(array $roles): self
    {
        $collator = \Collator::create('en-US');
        $collator->sort($roles);
        $this->roles = array_unique($roles);
        return $this;
    }
}
