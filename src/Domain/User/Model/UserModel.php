<?php

namespace Domain\User\Model;

use Application\Exceptions\ModelNotFoundException;
use DateTime;
use Domain\Common\Model\AbstractModel;
use Domain\User\Entity\User;
use Domain\User\Repository\UserRepository;
use Exception;
use function md5;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use function time;

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
     * @var UploadedFile|null
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
     * @var string
     */
    private $uploadDir;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var User|null
     */
    private $currentUser;

    /**
     * UserModel constructor.
     *
     * @param UserRepository $userRepo
     * @param Filesystem     $filesystem
     * @param string         $uploadDir
     */
    public function __construct(UserRepository $userRepo, Filesystem $filesystem, string $uploadDir)
    {
        $this->userRepo = $userRepo;
        $this->uploadDir = $uploadDir;
        $this->filesystem = $filesystem;
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
     * @param UploadedFile|null $photo
     *
     * @return UserModel
     */
    public function setPhoto(?UploadedFile $photo): UserModel
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
        $this->currentUser = $this->get();
        if ($this->isNew) {
            $this->currentUser->setCreatedAt()
                ->setIsActivated(false)
                ->setIsBlocked(false)
                ->setActivateCode(md5($this->email . time() . date('Y')));
        }
        $this->currentUser->setUpdatedAt();
        $this->currentUser->setActivateCode($this->activateCode);
        $this->fillRole();
        $this->fillPassword();
        $this->fillEmail();
        $this->fillBirthDate();
        $this->fillName();
        $this->fillPhone();
        $this->fillSex();
        $this->fillIsBlocked();
        $this->fillIsActivated();
        $this->fillPhoto();

        return $this->userRepo->save($this->currentUser);
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
            throw new ModelNotFoundException('User does not found by activation code');
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

    /**
     * @return UserModel
     */
    private function fillRole(): self
    {
        if (isset($this->role)) {
            $this->currentUser->setRole($this->role);
        }
        return $this;
    }

    /**
     * @return UserModel
     */
    private function fillPassword(): self
    {
        if (isset($this->password)) {
            $this->currentUser->setPassword($this->password);
        }
        return $this;
    }

    /**
     * @return UserModel
     */
    private function fillEmail(): self
    {
        if (isset($this->email)) {
            if (!$this->isNew && $this->currentUser->getEmail() !== $this->email) {
                $this->currentUser->setEmail($this->email);
            }
            if ($this->isNew) {
                $this->currentUser->setEmail($this->email);
            }
        }
        return $this;
    }

    /**
     * @return UserModel
     */
    private function fillBirthDate(): self
    {
        if (isset($this->birthDate)) {
            $this->currentUser->setBirthDate($this->birthDate);
        }
        return $this;
    }

    /**
     * @return UserModel
     */
    private function fillName(): self
    {
        if (isset($this->name)) {
            $this->currentUser->setName($this->name);
        }
        return $this;
    }

    /**
     * @return UserModel
     */
    private function fillPhone(): self
    {
        if (isset($this->phone)) {
            if (!$this->isNew && $this->currentUser->getPhone() !== $this->phone) {
                $this->currentUser->setPhone($this->phone);
            }
            if ($this->isNew) {
                $this->currentUser->setPhone($this->phone);
            }
        }
        return $this;
    }

    /**
     * @return UserModel
     */
    private function fillSex(): self
    {
        if (isset($this->sex)) {
            $this->currentUser->setSex($this->sex);
        }
        return $this;
    }

    /**
     * @return UserModel
     */
    private function fillIsBlocked(): self
    {
        if (isset($this->isBlocked)) {
            $this->currentUser->setIsBlocked($this->isBlocked);
        }
        return $this;
    }

    /**
     * @return UserModel
     */
    private function fillIsActivated(): self
    {
        if (isset($this->isActivated)) {
            $this->currentUser->setIsActivated($this->isActivated);
        }
        return $this;
    }

    /**
     * @return UserModel
     */
    private function fillPhoto(): self
    {
        if (isset($this->photo)) {
            if ($this->currentUser->getPhoto() && $this->filesystem->exists($this->uploadDir . '/' . $this->currentUser->getPhoto())) {
                $this->filesystem->remove($this->uploadDir . '/' . $this->currentUser->getPhoto());
            }
            $fileName = md5($this->photo->getClientOriginalName() . time()) . '.' . $this->photo->guessExtension();
            $this->currentUser->setPhoto($fileName);
            $this->photo->move($this->uploadDir, $fileName);
        }
        return $this;
    }
}
