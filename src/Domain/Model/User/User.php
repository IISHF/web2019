<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 11:50
 */

namespace App\Domain\Model\User;

use App\Domain\Model\Common\ChangeTracking;
use App\Utils\Text;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Class User
 *
 * @package App\Domain\Model\User
 *
 * @ORM\Entity(repositoryClass="UserRepository")
 * @ORM\Table(name="users")
 */
class User implements UserInterface
{
    use ChangeTracking;

    private const NO_PASSWORD = '<<no password>>';

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
     * @ORM\Column(name="email", type="string", length=128, unique=true)
     *
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(name="password", type="string", length=255)
     *
     * @var string
     */
    private $password;

    /**
     * @ORM\Column(name="roles", type="json")
     *
     * @var string[]
     */
    private $roles;

    /**
     * @ORM\Column(name="password_changed", type="datetime_immutable", nullable=true)
     *
     * @var \DateTimeImmutable|null
     */
    private $passwordChanged;

    /**
     * @ORM\Column(name="confirm_token", type="string", length=64, nullable=true)
     *
     * @var string|null
     */
    private $confirmToken;

    /**
     * @ORM\Column(name="reset_password_token", type="string", length=64, nullable=true)
     *
     * @var string|null
     */
    private $resetPasswordToken;

    /**
     * @ORM\Column(name="reset_password_until", type="datetime_immutable", nullable=true)
     *
     * @var \DateTimeImmutable|null
     */
    private $resetPasswordUntil;

    /**
     * @ORM\Column(name="last_login", type="datetime_immutable", nullable=true)
     *
     * @var \DateTimeImmutable|null
     */
    private $lastLogin;

    /**
     * @ORM\Column(name="last_login_ip", type="string", length=64, nullable=true)
     *
     * @var string|null
     */
    private $lastLoginIp;

    /**
     * @ORM\Column(name="last_login_user_agent", type="string", length=255, nullable=true)
     *
     * @var string|null
     */
    private $lastLoginUserAgent;

    /**
     * @ORM\Column(name="last_logout", type="datetime_immutable", nullable=true)
     *
     * @var \DateTimeImmutable|null
     */
    private $lastLogout;

    /**
     * @ORM\Column(name="last_login_failure", type="datetime_immutable", nullable=true)
     *
     * @var \DateTimeImmutable|null
     */
    private $lastLoginFailure;

    /**
     * @ORM\Column(name="last_login_failure_ip", type="string", length=64, nullable=true)
     *
     * @var string|null
     */
    private $lastLoginFailureIp;

    /**
     * @ORM\Column(name="last_login_failure_user_agent", type="string", length=255, nullable=true)
     *
     * @var string|null
     */
    private $lastLoginFailureUserAgent;

    /**
     * @ORM\Column(name="login_failures", type="smallint", options={"unsigned":true})
     *
     * @var int
     */
    private $loginFailures = 0;

    /**
     * @param string $id
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param array  $roles
     * @param string $confirmToken
     * @return self
     */
    public static function create(
        string $id,
        string $firstName,
        string $lastName,
        string $email,
        array $roles,
        string $confirmToken
    ): self {
        $user = new self($id, $firstName, $lastName, $email, $roles);
        $user->markUserAsUnconfirmed($confirmToken);
        return $user;
    }

    /**
     * @param string $id
     * @param string $firstName
     * @param string $lastName
     * @param string $email
     * @param array  $roles
     * @param string $password
     * @return self
     */
    public static function createConfirmed(
        string $id,
        string $firstName,
        string $lastName,
        string $email,
        array $roles,
        string $password
    ): self {
        $user = new self($id, $firstName, $lastName, $email, $roles);
        $user->markUserAsConfirmed($password);
        return $user;
    }

    /**
     * @param string   $id
     * @param string   $firstName
     * @param string   $lastName
     * @param string   $email
     * @param string[] $roles
     */
    private function __construct(
        string $id,
        string $firstName,
        string $lastName,
        string $email,
        array $roles
    ) {
        Assert::uuid($id);

        $this->id = $id;
        $this->setFirstName($firstName)
             ->setLastName($lastName)
             ->setEmail($email)
             ->setRoles($roles)
             ->initChangeTracking();

        $this->password = self::NO_PASSWORD;
    }

    /**
     * {@inheritdoc}
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
            return $this->getLastName() . ', ' . $this->getFirstName();
        }
        return $this->getFirstName() . ' ' . $this->getLastName();
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
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    private function setPassword(string $password): void
    {
        Assert::lengthBetween($password, 1, 128);
        $this->password           = $password;
        $this->confirmToken       = null;
        $this->resetPasswordToken = null;
        $this->resetPasswordUntil = null;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function changePassword(string $password): self
    {
        $this->setPassword($password);
        $this->passwordChanged = new \DateTimeImmutable('now');
        return $this;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function markUserAsConfirmed(string $password): self
    {
        $this->setPassword($password);
        return $this;
    }

    /**
     * @param string $confirmToken
     * @return $this
     */
    public function markUserAsUnconfirmed(string $confirmToken): self
    {
        $this->password           = self::NO_PASSWORD;
        $this->confirmToken       = hash('sha256', @hex2bin($confirmToken));
        $this->resetPasswordToken = null;
        $this->resetPasswordUntil = null;
        return $this;
    }

    /**
     * @return bool
     */
    public function isConfirmed(): bool
    {
        return $this->confirmToken === null;
    }

    /**
     * @param string $resetPasswordToken
     * @param string $until
     * @return $this
     */
    public function resetPassword(string $resetPasswordToken, string $until = '+ 15 minutes'): self
    {
        $this->resetPasswordToken = hash('sha256', @hex2bin($resetPasswordToken));
        $this->resetPasswordUntil = new \DateTimeImmutable($until);
        return $this;
    }

    /**
     * @param string $ip
     * @param string $userAgent
     * @param string $login
     * @return $this
     */
    public function registerLogin(string $ip, string $userAgent, string $login = 'now'): self
    {
        $this->resetPasswordToken = null;
        $this->resetPasswordUntil = null;
        $this->lastLogin          = new \DateTimeImmutable($login);
        $this->lastLoginIp        = $ip;
        $this->lastLoginUserAgent = Text::shorten($userAgent, 255);
        $this->loginFailures      = 0;
        return $this;
    }

    /**
     * @param string $logout
     * @return $this
     */
    public function registerLogout(string $logout = 'now'): self
    {
        $this->lastLogout = new \DateTimeImmutable($logout);
        return $this;
    }

    /**
     * @param string $ip
     * @param string $userAgent
     * @param string $failure
     * @return $this
     */
    public function registerLoginFailure(string $ip, string $userAgent, string $failure = 'now'): self
    {
        $this->lastLoginFailure          = new \DateTimeImmutable($failure);
        $this->lastLoginFailureIp        = $ip;
        $this->lastLoginFailureUserAgent = Text::shorten($userAgent, 255);
        $this->loginFailures++;
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
        $this->roles = array_unique($roles);
        return $this;
    }
}
