<?php

namespace Domain\User\Entity;

use DateTime;
use Domain\Shared\Entity\AbstractEntity;
use const PASSWORD_BCRYPT;
use function password_hash;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User
 * @package Domain\User\Entity
 */
class User extends AbstractEntity implements UserInterface
{
    const SEX_UNKNOWN = 0;
    const SEX_MALE = 1;
    const SEX_FEMALE = 2;

    const SEX_MAP = [
        'Неизвестно' => self::SEX_UNKNOWN,
        'Мужчина' => self::SEX_MALE,
        'Женщина' => self::SEX_FEMALE
    ];

    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_USER = 'ROLE_USER';

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var DateTime
     */
    private $birthDate;

    /**
     * @var int
     */
    private $sex = self::SEX_UNKNOWN;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $phone;

    /**
     * @var string
     */
    private $role;

    /**
     * just a hash
     * @var string|null
     */
    private $password;

    /**
     * @var string|null
     */
    private $photo;

    /**
     * @var bool
     */
    private $isBlocked = false;

    /**
     * @var bool
     */
    private $isActivated = false;

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return [$this->role];
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @return string|null
     */
    public function getSalt()
    {
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername(): string
    {
        return $this->getEmail();
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials(): bool
    {
        return false;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return User
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return User
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return User
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     * @return User
     */
    public function setRole(string $role): self
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getBirthDate(): ?DateTime
    {
        return $this->birthDate;
    }

    /**
     * @param DateTime $birthDate
     * @return User
     */
    public function setBirthDate(DateTime $birthDate): self
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    /**
     * @return int
     */
    public function getSex(): ?int
    {
        return $this->sex;
    }

    /**
     * @param int $sex
     * @return User
     */
    public function setSex(int $sex): self
    {
        $this->sex = $sex;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return User
     */
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    /**
     * @param string|null $photo
     * @return User
     */
    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;
        return $this;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(?string $password): self
    {
        if (empty($password)) {
            return $this;
        }
        $this->password = password_hash($password, PASSWORD_BCRYPT);
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsBlocked(): bool
    {
        return $this->isBlocked;
    }

    /**
     * @param bool $isBlocked
     * @return User
     */
    public function setIsBlocked(bool $isBlocked): User
    {
        $this->isBlocked = $isBlocked;
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsActivated(): bool
    {
        return $this->isActivated;
    }

    /**
     * @param bool $isActivated
     * @return User
     */
    public function setIsActivated(bool $isActivated): User
    {
        $this->isActivated = $isActivated;
        return $this;
    }


}
