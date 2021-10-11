<?php

namespace App\Controller;

use App\Entity\Etats;
use App\Entity\Inscriptions;
use App\Entity\Sorties;
use App\Form\SortieType;
use App\Repository\EtatsRepository;
use App\Repository\InscriptionsRepository;
use App\Repository\SortiesRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @Route("/sortie/inscrire/{id}", name="sortie_inscrire" , methods={"GET","POST"})
     */
    public function sInscrire(Sorties $sortie, EntityManagerInterface $em): Response
    {
        $inscriptions = new Inscriptions();
        
        $inscriptions->setDateInscription((new \DateTime()));
        $user = $this->getUser();
        $inscriptions->setNoParticipant($user);
        $inscriptions->setNoSortie($sortie);
        
        $em->persist($inscriptions);
        $em->flush();

        return $this->redirectToRoute('accueil');
    }

    /**
     * @Route("/sortie/desister/{id}", name="sortie_desister" , methods={"GET","POST"})
     */
    public function seDesister(Sorties $sortie, InscriptionsRepository $inscriptionsRepository  ,Request $req, EntityManagerInterface $em): Response
    {
        $inscriptions = new Inscriptions();

        $inscriptions = $inscriptionsRepository->findBy([
            'noSortie' => $sortie,
            'noParticipant'=> $this->getUser()
        ]);
        
        $em->remove($inscriptions);
        $em->flush();

        return $this->redirectToRoute('accueil');
    }

    /**
     * @Route("/sortie/afficher/{id}", name="sortie_afficher" , methods={"GET","POST"})
     */
    public function sortieAfficher(Sorties $sortie, InscriptionsRepository $inscriptionsRepository, Request $req): Response
    {
        
        $id = $sortie->getId();
        $listInscrit = $inscriptionsRepository->findBy([
            'noSortie'=> $id
        ]);

        return $this->renderForm('sortie/afficherSortie.html.twig', [
            'sortie' => $sortie,
            'inscrits'  =>  $listInscrit,
        ]);
    }

    /**
     * @Route("/sortie/modifier/{id}", name="sortie_modifier" , methods={"GET","POST"})
     */
    public function sortieModifier(Sorties $sortie, EntityManagerInterface $em, Request $req): Response
    {
       
        $form = $this->createForm(SortieType::class, $sortie);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $em->persist($sortie);
            $em->flush();

            return $this->redirectToRoute('accueil');
        }

        return $this->renderForm('sortie/modifierSortie.html.twig', [
           'form' => $form,
           'sortie' => $sortie
        ]);
    }

    /**
     * @Route("/sortie/supprimer/{id}", name="sortie_supprimer" , methods={"GET","POST"})
     */
    public function sortieSupprimer(Sorties $sortie,  Request $req, EntityManagerInterface $em): Response
    {       
        $em->remove($sortie);
        $em->flush();

        return $this->redirectToRoute('accueil');
    }


    /**
     * @Route("/sortie/annuler/{id}", name="sortie_annuler" , methods={"GET","POST"})
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
