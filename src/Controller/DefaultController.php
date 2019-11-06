<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Entity\Produit;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Route("/", name="default")
     */
    public function index()
    {

        return $this->render('default/index.html.twig', []);
    }

    /**
     * @Route("/getProduits", name="get_produits", methods={"GET"})
     */
    public function getProduits()
    {
        $json = [];
        $produits = $this->em->getRepository(Produit::class)->findAll();
        foreach($produits as $produit){
            $json[] = [
                'id' => $produit->getId(),
                'nomProduit' => $produit->getNomProduit(),
                'qteRestante' => $produit->getQteRestante()
            ];
        }
        return $this->json($json);

    }

    /**
     * @Route("/addActivite", name="add_activite", methods={"POST"})
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addActivite(Request $request)
    {
        $data = json_decode($request->getContent());
        $typeActivite = $data->typeActivite;
        $quantite = $data->quantite;
        $produitId = $data->produitId;

        $produit = $this->em->getRepository(Produit::class)->findOneBy(['id'=>$produitId]);
        if($produit!==null){
            $activite = new Activite();
            $activite->setQuantite($quantite);
            $activite->setProduit($produit);
            $activite->setTypeActivite($typeActivite);

            $this->em->persist($activite);
            $this->em->flush();

            return $this->json($produit->getQteRestante());
        }

        return $this->json('ko');

    }
}
