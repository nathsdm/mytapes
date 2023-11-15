<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Member;
use App\Form\MemberType;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class MemberController extends AbstractController
{
    #[Route('/member/index', name: 'app_member_index')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $members = $entityManager->getRepository(Member::class)->findAll();

        $hasAccess = $this->isGranted('ROLE_ADMIN');
        if(! $hasAccess) {
            throw $this->createAccessDeniedException("You cannot access member's list!");
        }

        return $this->render('member/index.html.twig', [
            'controller_name' => 'MemberController',
            'members' => $members
        ]);
    }

    #[Route('/member/{id}', name: 'app_member_show', requirements: ['id' => '\d+'], methods: ['GET'])]
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

    #[Route('/member/{id}/edit', name: 'app_member_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(Request $request, ManagerRegistry $doctrine, $id): Response
    {
        $member = $doctrine->getManager()->getRepository(Member::class)->find($id);
        $form = $this->createForm(MemberType::class, $member);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $member = $form->getData();
            $entityManager = $doctrine->getManager();
            $entityManager->persist($member);
            $entityManager->flush();
            return $this->redirectToRoute('app_profile_show');
        }
        return $this->render('member/edit.html.twig', [
            'controller_name' => 'MemberController',
            'form' => $form->createView()
        ]);
    }
}
