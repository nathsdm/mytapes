<?php
/**
 * Gestion de la page d'accueil de l'application
 *
 * @copyright  2017-2022 Telecom SudParis
 * @license    "MIT/X" License - cf. LICENSE file at project root
 */

namespace App\Controller;

use App\Entity\Inventory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Controleur Inventory
     */
#[Route('/inventory')]
class InventoryController extends AbstractController
{    
    #[Route('/', name: 'home', methods: ['GET'])]
    public function indexAction()
    {
        $htmlpage = '<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Welcome!</title>
    </head>
    <body>
        <h1>Welcome</h1>
            
    <p>Bienvenue dans notre inventaire</p>
    </body>
</html>';
        
        return new Response(
            $htmlpage,
            Response::HTTP_OK,
            array('content-type' => 'text/html')
            );
    }
    
    /**
     * Lists all inventories entities.
     */
    #[Route('/list', name: 'inventory_list', methods: ['GET'])]
    public function listAction(ManagerRegistry $doctrine)
    {
        $entityManager= $doctrine->getManager();
        $inventories = $entityManager->getRepository(Inventory::class)->findAll();

        // dump($todos);

        return $this->render('inventory/index.html.twig',
                [ 'inventories' => $inventories ]
                );
    }

    /**
     * Show an inventory
     *
     * @param Integer $id (note that the id must be an integer)
     */
    #[Route('/{id}', name: 'inventory_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(ManagerRegistry $doctrine, $id)
    {
        $InventoryRepo = $doctrine->getRepository(Inventory::class);
        $inventory = $InventoryRepo->find($id);

        if (!$inventory) {
                throw $this->createNotFoundException('The inventory does not exist');
        }
            
        return $this->render('inventory/show.html.twig',
                [ 'Inventory' => $inventory ]
                );
    }


}
