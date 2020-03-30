<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class MainController extends AbstractController
{
    /**
     * @Route("", name="homepage")
     */
    public function home(AuthenticationUtils $authenticationUtils,Request $request)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
       if ($this->isGranted('ROLE_PLANIFICATEUR')){
        return $this->redirectToRoute('planificateur_home');
       }
       else if ($this->isGranted('ROLE_ADMIN')){
        return $this->redirectToRoute('admin_home');
       }
       return $this->render("home.html.twig");
    }
     /**
     * @Route("/login", name="security_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // retrouver une erreur d'authentification s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        // retrouver le dernier identifiant de connexion utilisé
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            ]
        );
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logout(): void
    {
        throw new \Exception('This should never be reached!');
    }
}
