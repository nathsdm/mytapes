<?php

namespace App\Controller;

use App\Entity\Gallery;
use App\Form\GalleryType;
use App\Repository\GalleryRepository;
use App\Entity\Member;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/home')]
class HomeController extends AbstractController
{
    #[Route('', name: 'home', methods: ['GET'])]
    public function indexAction(EntityManagerInterface $entityManager)
    {
        $queryBuilder = $entityManager->createQueryBuilder();
        $queryBuilder->select('tape')
            ->from('App:Tape', 'tape')
            ->orderBy('tape.likes', 'DESC')
            ->setMaxResults(3);

        $tapes = $queryBuilder->getQuery()->getResult();

        return $this->render('index.html.twig', [
            'tapes' => $tapes,
        ]);
    }

    #[Route('/accessdenied', name: 'access_denied', methods: ['GET'])]
    public function accessDeniedAction()
    {
        return $this->render('access_denied.html.twig');
    }
}