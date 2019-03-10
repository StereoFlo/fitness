<?php

namespace Controller;

use Application\Exceptions\ModelNotFoundException;
use Application\Forms\ConfirmationFromType;
use Application\Forms\LoginFormType;
use Application\Forms\RegisterFormType;
use Domain\User\Entity\User;
use Domain\User\Model\UserModel;
use function md5;
use function mt_rand;
use function password_verify;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use function time;

/**
 * Class AuthController
 * @package Controller
 */
class AuthController extends BaseController
{
    /**
     * @var UserModel
     */
    private $userModel;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    /**
     * @var Session
     */
    private $session;
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * AuthController constructor.
     *
     * @param UserModel $userModel
     * @param TokenStorageInterface $tokenStorage
     * @param Session $session
     * @param EventDispatcherInterface $eventDispatcher
     */
    public function __construct(UserModel $userModel, TokenStorageInterface $tokenStorage, Session $session, EventDispatcherInterface $eventDispatcher)
    {
        $this->userModel = $userModel;
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * Регистрация в системе
     *
     * @return RedirectResponse|Response
     * @throws ModelNotFoundException
     */
    public function register(): Response
    {
        if (!$this->getUser()) {
            $form = $this->createForm(RegisterFormType::class, User::create())->handleRequest($this->request);
            if ($form->isSubmitted()) {
                $email = $this->userModel->setEmail($form->get('email')->getData())->getByEmail(true);
                if ($email) {
                    $form->addError(new FormError('email is taken'));
                }
                $phone = $this->userModel->setPhone($form->get('phone')->getData())->getByPhone(true);
                if ($phone) {
                    $form->addError(new FormError('phone is taken'));
                }
            }
            if ($form->isSubmitted() && $form->isValid()) {
                $this->userModel
                    ->setSex($form->get('sex')->getData())
                    ->setName($form->get('name')->getData())
                    ->setEmail($form->get('email')->getData())
                    ->setRole(User::ROLE_USER)
                    ->setBirthDate($form->get('birthDate')->getData())
                    ->setPhone($form->get('phone')->getData())
                    ->setActivateCode(md5(time() . mt_rand(0, 999)))
                    ->save();
                return $this->redirect('/');
            }
            return $this->render('auth/register.html.twig', ['form' => $form->createView()]);
        }
        return $this->redirect('/');
    }

    /**
     * Вход в систему
     *
     * @return Response
     * @throws ModelNotFoundException
     */
    public function login(): Response
    {
        $redirect = $this->request->query->get('redirect', '/');
        if (!$this->getUser()) {
            $form = $this->createForm(LoginFormType::class, User::create())->handleRequest($this->request);
            $user = null;
            if ($form->isSubmitted()) {
                $user = $this->userModel->setEmail($form->get('email')->getData())->getByEmail(true);
                if (empty($user)) {
                    $form->addError(new FormError('user does not exits'));
                }
                if ($user && $user->getIsBlocked()) {
                    $form->addError(new FormError('account is blocked'));
                }
                if ($user && !password_verify($form->get('password')->getData(), $user->getPassword())) {
                    $form->addError(new FormError('wrong password'));
                }
            }
            if ($form->isSubmitted() && $form->isValid()) {
                $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                $this->tokenStorage->setToken($token);
                $this->session->set('_security_main', serialize($token));
                $event = new InteractiveLoginEvent($this->request, $token);
                $this->eventDispatcher->dispatch('security.interactive_login', $event);
                return $this->redirect($redirect);
            }
            return $this->render('auth/login.html.twig', ['form' => $form->createView()]);
        }

        return $this->redirect($redirect);
    }

    /**
     * Выход
     *
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        $this->tokenStorage->setToken(null);
        $this->request->getSession()->invalidate();
        return $this->redirect('/');
    }

    /**
     * Подтверждение регистрации
     *
     * @param string $activateCode
     *
     * @return Response
     * @throws ModelNotFoundException
     */
    public function confirmation(string $activateCode): Response
    {
        $user = $this->userModel->setActivateCode($activateCode)->getByActivateCode();
        $form = $this->createForm(ConfirmationFromType::class)->handleRequest($this->request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userModel
                ->setId($user->getId())
                ->setPassword($form->get('password')->getData())
                ->setActivateCode(null)
                ->setIsActivated(true)
                ->save();
            return $this->redirect('/');
        }
        return $this->render('auth/confirmation.html.twig', ['form' => $form->createView()]);
    }

}
