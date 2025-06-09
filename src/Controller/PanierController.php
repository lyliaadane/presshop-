<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProduitsRepository;
use App\Entity\Produits;
use App\Entity\Promos;
use Symfony\Component\HttpFoundation\JsonResponse;

class PanierController extends AbstractController
{
    #[Route('/panier/add/{id}/{promo}', name: 'panier_add', defaults: ['promo' => 0])]
    public function add($id, $promo, SessionInterface $session, EntityManagerInterface $em, Request $request) 
    {
        $panier = $session->get('panier', []);

        $type = $promo ? 'promo' : 'produit';
        $key = $type . '-' . $id;

        if (!empty($panier[$key])) {
            $panier[$key]++;
        } else {
            $panier[$key] = 1;
        }

        $session->set('panier', $panier);

        if ($request->isXmlHttpRequest()) {
            return $this->json(['success' => true, 'count' => array_sum($panier)]);
        }
    
        return $this->redirectToRoute('app_produit_index');
    }


    #[Route('/panier/update/{id}/{promo}/{quantity}', name: 'panier_update', defaults: ['promo' => 0])]
    public function update($id, $promo, $quantity, SessionInterface $session, Request $request)
    {
        $panier = $session->get('panier', []);

        $type = $promo ? 'promo' : 'produit';
        $key = $type . '-' . $id;

        if ($quantity <= 0) {
            unset($panier[$key]);
        } else {
            $panier[$key] = $quantity;
        }

        $session->set('panier', $panier);

        if ($request->isXmlHttpRequest()) {
            return $this->json(['success' => true, 'count' => array_sum($panier)]);
        }

        return $this->redirectToRoute('app_panier');
    }

    
   #[Route('/panier/delete/{id}/{promo}', name: 'panier_delete', defaults: ['promo' => 0])]
    public function delete($id, $promo, SessionInterface $session,Request $request)
    {
        $panier = $session->get('panier', []);

        $type = $promo ? 'promo' : 'produit';
        $key = $type . '-' . $id;

        if (!empty($panier[$key])) {
            unset($panier[$key]);
        }

        $session->set('panier', $panier);
        if ($request->isXmlHttpRequest()) {
            return $this->json(['success' => true, 'count' => array_sum($panier)]);
        }

        return $this->redirectToRoute('app_panier');
    }

    
    #[Route('/panier', name: 'app_panier')]
    public function show(SessionInterface $session, EntityManagerInterface $em): Response
    {
        $panier = $session->get('panier', []);
        $items = [];
        $total = 0;

        foreach ($panier as $key => $quantity) {
            $parts = explode('-', $key);
            if (count($parts) < 2) {
                continue;
            }   
            [$type, $id] = $parts;

            if ($type === 'promo') {
                $promo = $em->getRepository(Promos::class)->find($id);
                if (!$promo) continue;
                $produit = $promo->getIdProduit();
                $prix = $promo->getMontant();

                $items[] = [
                    'produit' => $produit,
                    'isPromo' => true,
                    'quantity' => $quantity,
                    'prix' => $prix,
                    'id' => $id,          // id de la promo
                ];
            } else {
                $produit = $em->getRepository(Produits::class)->find($id);
                if (!$produit) continue;
                $prix = $produit->getPrix();

                $items[] = [
                    'produit' => $produit,
                    'isPromo' => false,
                    'quantity' => $quantity,
                    'prix' => $prix,
                    'id' => $id,          // id du produit
                ];
            }

            $total += $prix * $quantity;
        }

        return $this->render('panier/index.html.twig', [
            'items' => $items,
            'total' => $total
        ]);
    }


}
