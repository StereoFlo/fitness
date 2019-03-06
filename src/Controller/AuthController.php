<?php

namespace Controller;

use Application\Exceptions\ModelNotFoundException;
use Application\Exceptions\ValidationException;
use Application\Forms\LoginFormType;
use Application\Forms\RegisterFormType;
use Domain\User\Entity\User;
use Domain\User\Model\UserModel;
use function password_verify;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;

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
                    ->save();
                return $this->redirect('/');
            }
            return $this->render('auth/register.html.twig', ['form' => $form->createView()]);
        }
        return $this->redirect('/');
    }

    /**
     * @return Response
     * @throws ModelNotFoundException
     * @throws ValidationException
     */
    public function login(): Response
    {
        if (!$this->getUser()) {
            $form = $this->createForm(LoginFormType::class, User::create())->handleRequest($this->request);
            if ($form->isSubmitted() && $form->isValid()) {
                $user = $this->userModel->setEmail($form->get('email')->getData())->getByEmail();
                if (!password_verify($form->get('password')->getData(), $user->getPassword())) {
                    throw new ValidationException('passwords not match');
                }

                $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
                $this->tokenStorage->setToken($token);
                $this->session->set('_security_main', serialize($token));
                $event = new InteractiveLoginEvent($this->request, $token);
                $this->eventDispatcher->dispatch('security.interactive_login', $event);
            }
            return $this->render('auth/login.html.twig', ['form' => $form->createView()]);
        }

        return $this->redirect('/');
    }

    /**
     * @return RedirectResponse
     */
    public function logout(): RedirectResponse
    {
        $this->tokenStorage->setToken(null);
        $this->request->getSession()->invalidate();
        return RedirectResponse::create('/');
    }

}
