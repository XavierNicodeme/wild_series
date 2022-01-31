<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Repository\ActorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActorController extends AbstractController
{
    /**
     * @Route("/actor", name="actor")
     */
    public function index(ManagerRegistry $managerRegistry): Response
    {
        $actors = $managerRegistry->getRepository(Actor::class)->findAll();
        return $this->render('actor/index.html.twig', [
            'controller_name' => 'ActorController',
            'actors' => $actors,
        ]);
    }

    /**
     * @Route("/actor/{id}", name="actor_show")
     */
    public function show(Actor $actor): Response
    {
        return $this->render('actor/show.html.twig', [
            'actor' => $actor
        ]);
    }
}
