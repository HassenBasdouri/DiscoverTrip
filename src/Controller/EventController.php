<?php

namespace App\Controller;

use App\Entity\Event;
use App\Entity\Image;
use App\Entity\Participation;
use App\Form\EventType;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_USER")
 * @Route("/event")
 */
class EventController extends AbstractController
{
    /**
     * @Route("/", name="event_index", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('event/index.html.twig', [
            'events' => $this->getUser()->getMyEvents(),
        ]);
    }

    /**
     * @Route("/new", name="event_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fu): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $files=$request->files->get('event')['images'];
            foreach ($files as $file){
                $image=new Image();
                $image->setPath($fu->upload($file));
                $event->addImage($image);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $event->setPlanner($this->getUser());
            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="event_show", methods={"GET"})
     */
    public function show(Event $event): Response
    {
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="event_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Event $event, FileUploader $fu): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $files=$request->files->get('event')['images'];
            foreach ($files as $file){
                $image=new Image();
                $image->setPath($fu->upload($file));
                $event->addImage($image);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="event_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Event $event): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($event);
            $entityManager->flush();
        }

        return $this->redirectToRoute('event_index');
    }
    
     /**
     * @Route("/participate/{id}", name="event_participation", methods={"GET"})
     */
    public function participate( Event $event): Response
    {
        $participation = new Participation();
        $participation->setParticipant($this->getUser());
        $participation->setEvent($event);
        $participation->setPayment(true);
        $this->getDoctrine()->getManager()->persist($participation);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('homepage');
    }
    /**
     * @Route("/cancel/participation/{id}", name="cancel_participation", methods={"GET"})
     */
    public function cancel( Request $request,Participation $participation): Response
    {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($participation);
            $entityManager->flush();
        return $this->redirectToRoute('homepage');
    }
    /**
     * @Route("/my/participations", name="participations")
     */
    public function participations(): Response
    {
        return $this->render('participation/index.html.twig', [
            'participations' => $this->getUser()->getParticipation(),
        ]);
    }
}
