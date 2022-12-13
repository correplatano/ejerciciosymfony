<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\User;
use App\Form\Type\UserType;
use App\Repository\UserRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    /**
    * @Route("/login", name="app_login")
    */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        //con el getUser() comprobamos si el usuario ya esta logueado
        if ($this->getUser()) {
             return $this->redirectToRoute('productostodos');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
    * @Route("/logout", name="app_logout")
    */
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
    /**
    * @Route("/newUser", name="app_login")
    */
    public function nuevo(Request $request, UserRepository $repo, UserPasswordHasherInterface  $encoder)
    {
        $usuario = new User();
        $form = $this->createForm(UserType::class, $usuario);
        
        $form->handleRequest($request);

        if($form->isSubmitted()) {
           // if($form->isValid()) {
                $user = $form->getData();

                if($user->getPassword()!="") {
                    $hashedPassword = $encoder->hashPassword(
                        $user,
                        $user->getPassword()
                    );
                    $user->setPassword($hashedPassword);
                }
                $rol = $form->get('rol')->getData();
                $user->setRoles([$rol]);

                $repo->add($user, true);

                return $this->redirectToRoute("app_login");
            //}
        }

        return $this->render('register.html.twig', [
            'formulario' => $form->createView()
        ]);
    }
}
