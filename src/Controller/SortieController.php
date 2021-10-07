<?php

namespace App\Controller;

use App\Entity\Etats;
use App\Entity\Sorties;
use App\Repository\SortiesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SortieController extends AbstractController
{
    /**
     * @Route("/sortie", name="sortie")
     */
    public function index(): Response
    {
        return $this->render('sortie/accueil.html.twig', [
            'controller_name' => 'SortieController',
        ]);
    }

    /**
     * @Route("/sortie/annuler/{id}", name="sortie_annuler")
     */
    public function annuler(Sorties $sortie ,SortiesRepository $sortiesRepository ,EntityManagerInterface  $em, Request $request): Response
    {

        $id=$sortie->getId();

        $sorties= $sortiesRepository->findById($id);

        $motif = $request->get("motif");
        $sortie = new Sorties();
        $sortie ->setmotifAnnulation($motif);

        $etat= new Etats();
        $etat->setLibelle('Annulée');
        $sortie->setNoEtat($etat);

        $em->persist($etat);
        $em->persist($sortie);
        $em->flush();

        return $this->render('sortie/annulerSortie.html.twig', [
           'sorties' => $sorties,
        ]);
    }
}
