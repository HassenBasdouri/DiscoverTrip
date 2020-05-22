<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
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
