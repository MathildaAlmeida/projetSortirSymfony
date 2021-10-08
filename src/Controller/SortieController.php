<?php

namespace App\Controller;

use App\Entity\Etats;
use App\Entity\Sorties;
use App\Form\SortieType;
use App\Repository\EtatsRepository;
use App\Repository\InscriptionsRepository;
use App\Repository\SortiesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Config\Doctrine\Orm\EntityManagerConfig;

class SortieController extends AbstractController
{
    /**
     * @Route("/sortie/ajout", name="sortie_ajout")
     */
    public function sortieAjout(EntityManagerInterface $em, Request $req): Response
    {
        $sortie = new Sorties();
        $formSortie = $this->createForm(SortieType::class, $sortie);
        $formSortie->handleRequest($req);

        return $this->renderForm('sortie/ajoutsortie.html.twig', [
            'formSortie' => $formSortie,
        ]);
    }

    /**
     * @Route("/sortie/afficher/{id}", name="sortie_afficher")
     */
    public function sortieAfficher(Sorties $sortie, InscriptionsRepository $inscriptionsRepository, Request $req): Response
    {
        $user = $this->getUser();
        $listInscrit = $inscriptionsRepository->findBy([
            'noParticipant' => $user->getId()
        ]);

        return $this->renderForm('sortie/afficherSortie.html.twig', [
            'sortie' => $sortie,
            'inscrits'  =>  $listInscrit,
        ]);
    }

    /**
     * @Route("/sortie/modifier/{id}", name="sortie_modifier")
     */
    public function sortieModifier(Sorties $sortie,  Request $req): Response
    {
       
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($req);

        return $this->renderForm('sortie/modifierSortie.html.twig', [
           'form' => $form,
           'sortie' => $sortie
        ]);
    }

    /**
     * @Route("/sortie/supprimer/{id}", name="sortie_supprimer")
     */
    public function sortieSupprimer(Sorties $sortie,  Request $req, EntityManagerInterface $em): Response
    {
       
        $em->remove($sortie);
        $em->flush();

        return $this->redirectToRoute('accueil');
    }


    /**
     * @Route("/sortie/annuler/{id}", name="sortie_annuler")
     */
    public function annuler(Sorties $sortie ,EtatsRepository $etatsRepository , SortiesRepository $sortiesRepository ,EntityManagerInterface  $em, Request $request): Response
    {
        
        if ($request->isMethod('POST')) {

            $motif = $request->get("motif");
            $sortie ->setmotifAnnulation($motif);
            $etat = $etatsRepository->find(6);
        
            $sortie->setNoEtat($etat); 
    
            $em->persist($sortie);
            $em->flush();

            return $this->redirectToRoute('accueil');
        }
                

        return $this->render('sortie/annulerSortie.html.twig', [
           'sortie' => $sortie,
        ]);
    }

}
