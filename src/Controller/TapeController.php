<?php

namespace App\Controller;

use App\Entity\Tape;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TapeController extends AbstractController
{
    /**
     * Show an inventory tape
     * @param Integer $id (note that the id must be an integer)
     */
    #[Route('tape/{id}', name: 'tape_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(ManagerRegistry $doctrine, $id)
    {
        $entityManager = $doctrine->getManager();
        $tape = $entityManager->getRepository(Tape::class)->find($id);
        return $this->render('tape/show.html.twig', [
            'Tape' => $tape,
        ]);
    }
}
