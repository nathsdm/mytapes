<?php
/**
 * Gestion de la page d'accueil de l'application
 *
 * @copyright  2017-2022 Telecom SudParis
 * @license    "MIT/X" License - cf. LICENSE file at project root
 */

namespace App\Controller;

use App\Entity\Inventory;
use App\Entity\Member;
use App\Entity\Tape;
use App\Form\InventoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Controleur Inventory
     */
#[Route('/inventory')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class InventoryController extends AbstractController
{
    /**
     * Show an inventory
     *
     * @param Integer $id (note that the id must be an integer)
     */
    #[Route('/{id}', name: 'app_inventory_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(ManagerRegistry $doctrine, $id)
    {
        $inventoryRepo = $doctrine->getRepository(Inventory::class);
        $inventory = $inventoryRepo->find($id);

        $hasAccess = $this->isGranted('ROLE_ADMIN') || ($this->getUser()->getMember($doctrine) == $inventory->getMember());
        if(! $hasAccess) {
            throw $this->createAccessDeniedException("You cannot access another member's inventory!");
        }

        if (!$inventory) {
                throw $this->createNotFoundException('The inventory does not exist');
        }
            
        return $this->render('inventory/show.html.twig',
                [ 'inventory' => $inventory ]
                );
    }

    #[Route('/{id}/edit', name: 'app_inventory_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Inventory $inventory, EntityManagerInterface $entityManager, ManagerRegistry $doctrine): Response
    {
        $form = $this->createForm(InventoryType::class, $inventory, [
            'member' => $inventory->getMember()
        ]);
        $form->handleRequest($request);

        $previousTapes = $inventory->getTapes();

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();

            return $this->redirectToRoute('app_inventory_edit', ['id' => $inventory->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('inventory/edit.html.twig', [
            'inventory' => $inventory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_inventory_delete', methods: ['POST'])]
    public function delete(Request $request, Inventory $inventory, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$inventory->getId(), $request->request->get('_token'))) {
            $entityManager->remove($inventory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_profile_show', [], Response::HTTP_SEE_OTHER);
    }
}
