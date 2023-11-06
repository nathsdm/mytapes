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
    #[Route('tape/{id}', name: 'tape_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(Request $request, ManagerRegistry $doctrine, $id)
    {
        $entityManager = $doctrine->getManager();
        $tape = $entityManager->getRepository(Tape::class)->find($id);

        // Retrieve the $page and $id_page parameters from the Request object
        $page = $request->query->get('page');
        $id_page = $request->query->get('id_page');

        // Use the $page and $id_page variables to generate the route name for the back link
        $backRoute = 'app_' . $page . '_show';
        $backRouteParams = ['id' => $id_page];

        return $this->render('tape/show.html.twig', [
            'Tape' => $tape,
            'back_route' => $backRoute,
            'back_route_params' => $backRouteParams
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

    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }
}
