<?php

namespace App\Controller\User;

use App\Entity\User\User;
use App\Exception\User\RoleNotFoundException;
use App\Form\User\UserType;
use App\Manager\User\UserManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Exception\User\UserAlreadyExistsException;

class UserController extends Controller
{
    private $userManager;

    public function __construct(UserManagerInterface $userManager)
    {
        $this->userManager = $userManager;
    }

    /**
     * @Route("/user", name="create_user")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            try {
                $this->userManager
                    ->create($user, UserManagerInterface::TYPE_USER);
                return $this->redirectToRoute('homepage');

            } catch (UserAlreadyExistsException | RoleNotFoundException $exception) {
                return $this->render('common/error.html.twig',
                    ['error_message' => $exception->getMessage()]);
            }
        }

        return $this->render('user/user/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
