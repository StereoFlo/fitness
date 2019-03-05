<?php

namespace Domain\User\Model;

use Application\Exceptions\ModelNotFoundException;
use DateTime;
use Domain\Shared\Model\AbstractModel;
use Domain\User\Entity\User;
use Domain\User\Repository\UserRepository;
use function strtotime;

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
    private $role;

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
     * @param string $birthDate
     * @return UserModel
     */
    public function setBirthDate(string $birthDate): UserModel
    {
        $this->birthDate = DateTime::createFromFormat( 'Y-m-d H:i:s', strtotime($birthDate));
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
     * @return User
     * @throws ModelNotFoundException
     * @throws \Exception
     */
    public function save(): User
    {
        $user = $this->get();
        if ($this->isNew) {
            $user->setCreatedAt();
        }
        $user->setUpdatedAt()
            ->setRole($this->role)
            ->setPassword($this->password)
            ->setEmail($this->email)
            ->setBirthDate($this->birthDate)
            ->setName($this->name)
            ->setPhone($this->phone)
            //->setPhoto() todo
            ->setSex($this->sex)
            ->setIsActivated(true)
            ->setIsBlocked(false);

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
}
