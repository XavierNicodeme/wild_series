<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Entity\Program;
use App\Entity\Season;
use App\Form\CategoryType;
use App\Form\ProgramType;
use App\Service\Slugify;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
     * @Route("/new", name="new")
     */
    public function new(Request $request, ManagerRegistry $managerRegistry, Slugify $slugify, MailerInterface $mailer): Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $managerRegistry->getManager();
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $program->setOwner($this->getUser());
            $entityManager->persist($program);
            $entityManager->flush();

            $email = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to('test@mail.com')
                ->subject('Une nouvelle série a été ajouté')
                ->html($this->renderView('program/newProgramEmail.html.twig', [
                    'program' => $program,
                ]));

            $mailer->send($email);

            return $this->redirectToRoute('program_index');
        }

        return $this->renderForm('program/new.html.twig', [
            'form' => $form,
        ]);
    }
    /**
     * @Route("/{slug}", methods={"GET"}, name="show")
     */
    public function show(Program $program)
    {
        return $this->render('program/show.html.twig', [
            'program' => $program,
        ]);
    }

    /**
     * @Route("/{slug}/seasons/{season}", requirements={"season"="\d+"}, methods={"GET"}, name="season_show")
     * @ParamConverter("program", options={"mapping": {"slug": "slug"}})
     */

    public function showSeason(Program $program, Season $season): Response
    {

        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
        ]);
    }

    /**
     * @Route("/{slug}/season/{season}/episode/{episode}", requirements={"episode"="\d+"}, methods={"GET"}, name="episode_show")
     * @ParamConverter("program", options={"mapping": {"slug": "slug"}})
     */

    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {
        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode
            ]);
    }
    /**
     * @Route("/{slug}/edit", name="edit")
     */
    public function edit(Program $program, Request $request, ManagerRegistry $managerRegistry): Response
    {
        if (!$this->getUser() === $program->getOwner()) {
            throw new AccessDeniedException('Only the owner can edit the program');
        } else {
            $form = $this->createForm(ProgramType::class, $program);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $managerRegistry->getManager()->flush();

                return $this->redirectToRoute('program_index');
            }

            return $this->renderForm('program/edit.html.twig', [
                'form' => $form,
                'program' => $program,
            ]);
        }
    }
}