<?php

namespace App\Controller;

use App\Entity\Tape;
use App\Form\TapeType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/tape')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class TapeController extends AbstractController
{
    /**
     * Show an inventory tape
     * @param Integer $id (note that the id must be an integer)
     */
    #[Route('/{id}', name: 'tape_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Request $request, ManagerRegistry $doctrine, $id)
    {
        $entityManager = $doctrine->getManager();
        $tape = $entityManager->getRepository(Tape::class)->find($id);
        if ($this->getUser() == null) {
            $user_has_liked_tape = null;
        } else {
            $memberId = $this->getUser()->getMember($doctrine)->getId();
            $user_has_liked_tape = $tape->isLikedByMember($memberId);
        }

        return $this->render('tape/show.html.twig', [
            'Tape' => $tape,
            'user_has_liked_tape' => $user_has_liked_tape,
        ]);
    }

    #[Route('/new', name: 'app_tape_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ManagerRegistry $doctrine): Response
    {
        $tape = new Tape();
        $form = $this->createForm(TapeType::class, $tape);
        $form->handleRequest($request);

        $member = $this->getUser()->getMember($doctrine);
        $inventory = $member->getInventory()[0];
        $tape->setInventory($inventory);

        if ($form->isSubmitted() && $form->isValid()) {

            // Change content-type according to image's
            $imagefile = $tape->getImageFile();
            if($imagefile) {
                    $mimetype = $imagefile->getMimeType();
                    $tape->setContentType($mimetype);
            }

            $entityManager = $doctrine->getManager();
            $entityManager->persist($tape);
            $entityManager->flush();

            return $this->redirectToRoute('app_profile_show', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tape/new.html.twig', [
            'tape' => $tape,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tape_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tape $tape, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(TapeType::class, $tape);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Change content-type according to image's
            $imagefile = $tape->getImageFile();
            if($imagefile) {
                    $mimetype = $imagefile->getMimeType();
                    $tape->setContentType($mimetype);
            }

            $doctrine->getManager()->flush();

            return $this->redirectToRoute('tape_show', ['id' => $tape->getId(), 'page' => 'profile'], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tape/edit.html.twig', [
            'tape' => $tape,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'app_tape_delete', methods: ['POST'])]
    public function delete(Request $request, Tape $tape, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tape->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tape);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_profile_show', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/likes', name: 'app_tape_like', methods: ['POST'])]
    public function likes(Request $request, Tape $tape, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $userId = $this->getUser()->getMember($doctrine)->getId();
        $tape->addMemberLike($userId);
        $entityManager->flush();

        return $this->redirectToRoute('tape_show', ['id' => $tape->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/dislikes', name: 'app_tape_dislike', methods: ['POST'])]
    public function dislikes(Request $request, Tape $tape, ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $tape->removeMemberLike($this->getUser()->getMember($doctrine)->getId());
        $entityManager->flush();

        return $this->redirectToRoute('tape_show', ['id' => $tape->getId()], Response::HTTP_SEE_OTHER);
    }
}
