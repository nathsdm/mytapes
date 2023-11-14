<?php

namespace App\Controller\Admin;

use App\Entity\Inventory;
use App\Entity\Tape;
use App\Entity\Member;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class DashboardController extends AbstractDashboardController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Count members
        $memberCount = $this->entityManager
            ->getRepository(Member::class)
            ->count([]);

        // Count tapes
        $tapeCount = $this->entityManager
            ->getRepository(Tape::class)
            ->count([]);

        // redirect to crud index page
        return $this->render('admin/dashboard.html.twig', [
            'memberCount' => $memberCount,
            'tapeCount' => $tapeCount,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Mytapes Admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Inventory', 'fas fa-list', Inventory::class);
        yield MenuItem::linkToCrud('Tape', 'fas fa-list', Tape::class);
        yield MenuItem::linkToCrud('Member', 'fas fa-list', Member::class);
    }
}
