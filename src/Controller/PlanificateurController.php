<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
/**
 * @Route("/planificateur")
 */
class PlanificateurController extends AbstractController
{
    /**
     * @Route("/home", name="planificateur_home")
     */
    public function index_planificateru()
    {
        return $this->render('planificateur/index.html.twig');
    }
}
