<?php

namespace App\Controller;

use App\Entity\Program;
use App\Entity\Season;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/program", name="program_")
 */
class ProgramController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(ManagerRegistry $managerRegistry): Response
    {
        $entityManager = $managerRegistry->getRepository(Program::class);
        $programs = $entityManager->findAll();

        return $this->render('program/index.html.twig', [
            'programs' => $programs,
        ]);
    }
    /**
     * @Route("/{id}", requirements={"id"="\d+"}, methods={"GET"}, name="show")
     */
    public function show(int $id, ManagerRegistry $managerRegistry)
    {
        $program = $managerRegistry
            ->getRepository(Program::class)
            ->findOneBy(['id' => $id]);

        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : '.$id.' found in program\'s table.'
            );
        }

        return $this->render('program/show.html.twig', [
            'program' => $program,
        ]);
    }

    /**
     * @Route("/{programId}/seasons/{seasonId}", requirements={"seasonId"="\d+"}, methods={"GET"}, name="season_show")
     */

    public function showSeason(int $programId, int $seasonId, ManagerRegistry $managerRegistry): Response
    {
        $season = $managerRegistry
            ->getRepository(Season::class)
            ->findOneBy(
                ['id' => $seasonId]
            );

        return $this->render('program/season_show.html.twig', [
            'season' => $season,
        ]);
    }

}