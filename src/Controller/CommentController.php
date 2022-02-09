<?php

namespace App\Controller;

use App\DataFixtures\UserFixtures;
use App\Entity\Comment;
use App\Entity\User;
use App\Form\CommentType;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @route("/comment", name="comment_")
 */
class CommentController extends AbstractController
{
    /**
     * @Route("/{id}/edit", name="edit")
     */
    public function edit(Comment $comment, Request $request, ManagerRegistry $managerRegistry): Response
    {
        if ($comment->getAuthor() === $this->getUser() || in_array(User::ADMIN, $this->getUser()->getRoles())) {
            $form = $this->createForm(CommentType::class, $comment);
            $form->handleRequest($request);
            $episode = $comment->getEpisode();
            if ($form->isSubmitted() && $form->isValid()) {
                $managerRegistry->getManager()->flush();

                return $this->redirectToRoute('episode_show', ['slug' => $episode->getSlug()], Response::HTTP_SEE_OTHER);
            }
            return $this->renderForm('comment/edit.html.twig', [
                'form' => $form
            ]);
        } else {
            throw new AccessDeniedException('Only author can edit theirs comments');
        }
    }
    /**
     * @Route("/{id}/delete", name="delete")
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete(Comment $comment, ManagerRegistry $managerRegistry):Response
    {
        $episode = $comment->getEpisode();
        $managerRegistry->getManager()->remove($comment);
        $managerRegistry->getManager()->flush();

        return $this->redirectToRoute('episode_show', ['slug' => $episode->getSlug()], Response::HTTP_SEE_OTHER);
    }
}
