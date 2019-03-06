<?php

namespace Domain\User\Model;

use Application\Exceptions\ModelNotFoundException;
use DateTime;
use Domain\Shared\Model\AbstractModel;
use Domain\User\Entity\User;
use Domain\User\Repository\UserRepository;
use Exception;
use function md5;

/**
 * Class UserModel
 * @package Domain\User\Model
 */
class UserModel extends AbstractModel
{
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
    private $sex = User::SEX_UNKNOWN;

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
    private $role = User::ROLE_USER;

    /**
     * just a hash
     * @var string
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
     * @var string|null
     */
    private $activateCode;

    /**
     * @var UserRepository
     */
    private $userRepo;

    /**
     * UserModel constructor.
     * @param UserRepository $userRepo
     */
    public function __construct(UserRepository $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    /**
     * @param int $id
     * @return UserModel
     */
    public function setId(?int $id): UserModel
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $name
     * @return UserModel
     */
    public function setName(string $name): UserModel
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param DateTime $birthDate
     * @return UserModel
     */
    public function setBirthDate(DateTime $birthDate): UserModel
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    /**
     * @param int $sex
     * @return UserModel
     */
    public function setSex(int $sex): UserModel
    {
        $this->sex = $sex;
        return $this;
    }

    /**
     * @param string $email
     * @return UserModel
     */
    public function setEmail(string $email): UserModel
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param string $phone
     * @return UserModel
     */
    public function setPhone(string $phone): UserModel
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @param string $role
     * @return UserModel
     */
    public function setRole(string $role): UserModel
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @param string $password
     * @return UserModel
     */
    public function setPassword(string $password): UserModel
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @param string|null $photo
     * @return UserModel
     */
    public function setPhoto(?string $photo): UserModel
    {
        $this->photo = $photo;
        return $this;
    }

    /**
     * @param string|null $activateCode
     *
     * @return UserModel
     */
    public function setActivateCode(?string $activateCode): UserModel
    {
        $this->activateCode = $activateCode;
        return $this;
    }

    /**
     * @param bool $isBlocked
     *
     * @return UserModel
     */
    public function setIsBlocked(bool $isBlocked): UserModel
    {
        $this->isBlocked = $isBlocked;
        return $this;
    }

    /**
     * @param bool $isActivated
     *
     * @return UserModel
     */
    public function setIsActivated(bool $isActivated): UserModel
    {
        $this->isActivated = $isActivated;
        return $this;
    }

    /**
     * @return User
     * @throws ModelNotFoundException
     * @throws Exception
     */
    public function save(): User
    {
        $user = $this->get();
        if ($this->isNew) {
            $user->setCreatedAt()
                ->setIsActivated(false)
                ->setIsBlocked(false)
                ->setActivateCode(md5($this->email . time() . date('Y')));
        }
        $user->setUpdatedAt();
        if (isset($this->role)) {
            $user->setRole($this->role);
        }
        if (isset($this->password)) {
            $user->setPassword($this->password);
        }
        if (isset($this->email)) {
            $user->setEmail($this->email);
        }
        if (isset($this->birthDate)) {
            $user->setBirthDate($this->birthDate);
        }
        if (isset($this->name)) {
            $user->setName($this->name);
        }
        if (isset($this->phone)) {
            $user->setPhone($this->phone);
        }
        if (isset($this->sex)) {
            $user->setSex($this->sex);
        }
        if (isset($this->activateCode)) {
            $user->setActivateCode($this->activateCode);
        }
        if (isset($this->isBlocked)) {
            $user->setIsBlocked($this->isBlocked);
        }
        if (isset($this->isActivated)) {
            $user->setIsActivated($this->isActivated);
        }
            //->setPhoto() todo

        return $this->userRepo->save($user);
    }

    /**
     * @return User
     * @throws ModelNotFoundException
     */
    public function get(): User
    {
        if (!isset($this->id)) {
            $this->isNew = true;
            return User::create();
        }
        $user = $this->userRepo->getById($this->id);
        if (empty($user)) {
            throw new ModelNotFoundException('User does not found by id');
        }
        return $user;
    }

    /**
     * @param bool $doNotThrow
     *
     * @return User
     * @throws ModelNotFoundException
     */
    public function getByEmail(bool $doNotThrow = false): ?User
    {
        $user = $this->userRepo->getByEmail($this->email);
        if ($doNotThrow) {
            return $user;
        }
        if (empty($user)) {
            throw new ModelNotFoundException('User does not found by email');
        }
        return $user;
    }

    /**
     * @param bool $doNotThrow
     *
     * @return User
     * @throws ModelNotFoundException
     */
    public function getByPhone(bool $doNotThrow = false): ?User
    {
        $user = $this->userRepo->getByPhone($this->phone);
        if ($doNotThrow) {
            return $user;
        }
        if (empty($user)) {
            throw new ModelNotFoundException('User does not found by phone');
        }
        return $user;
    }

    /**
     * @param bool $doNotThrow
     *
     * @return User
     * @throws ModelNotFoundException
     */
    public function getByActivateCode(bool $doNotThrow = false): ?User
    {
        $user = $this->userRepo->getByActivateCode($this->activateCode);
        if ($doNotThrow) {
            return $user;
        }
        if (empty($user)) {
            throw new ModelNotFoundException('User does not found by active code');
        }
        return $user;
    }

    /**
     * @param int $limit
     * @param int $offset
     *
     * @return array
     */
    public function getList(int $limit, int $offset): array
    {
        return $this->userRepo->getUserList($limit, $offset);
    }
}
