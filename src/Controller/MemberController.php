<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Member;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

class MemberController extends AbstractController
{
    #[Route('/member/index', name: 'app_member_index')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $members = $entityManager->getRepository(Member::class)->findAll();

        return $this->render('member/index.html.twig', [
            'controller_name' => 'MemberController',
            'members' => $members
        ]);
    }

    #[Route('member/{id}', name: 'app_member_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(ManagerRegistry $doctrine, $id): Response
    {
        return $this->render('member/show.html.twig', [
            'controller_name' => 'MemberController',
            'member' => $doctrine->getManager()->getRepository(Member::class)->find($id)
        ]);
    }

    #[Route('/profile', name: 'app_profile_show')]
    public function profile(ManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        $member = $doctrine->getManager()->getRepository(Member::class)->findOneBy(['User' => $user]);
        return $this->render('member/profile.html.twig', [
            'controller_name' => 'MemberController',
            'member' => $member
        ]);
    }
}
