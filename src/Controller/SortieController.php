<?php

namespace App\Controller;

use App\Entity\Etats;
use App\Entity\Inscriptions;
use App\Entity\Lieux;
use App\Entity\Sorties;
use App\Entity\Villes;
use App\Form\SortieType;
use App\Repository\LieuxRepository;
use App\Repository\EtatsRepository;
use App\Repository\InscriptionsRepository;
use App\Repository\SortiesRepository;
use App\Repository\VillesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;


class SortieController extends AbstractController
{
    /**
     * @Route("/sortie/ajout", name="sortie_ajout")
     */
    public function sortieAjout(EntityManagerInterface $em,VillesRepository $vr,Request $req, EtatsRepository $er): Response
    {
        $sortie = new Sorties();
        $formSortie = $this->createForm(SortieType::class, $sortie);
        $villes = $vr->findAll();
        $formSortie->handleRequest($req);
        if ($formSortie->isSubmitted() && $formSortie->isValid()) {

            $sortie->setOrganisateur($this->getUser());
            $etat = $er->findOneBy(['libelle' => 'En création']);
            $sortie->setNoEtat($etat);
            $em->persist($sortie);
            $em->flush();

            return $this->redirectToRoute('accueil');
        }

        return $this->renderForm('sortie/ajoutsortie.html.twig', [
            'formSortie' => $formSortie,
            'villes' => $villes
        ]);
    }

    /**
     * @Route("/lieux_ville/{ville}", name="lieux_ville", methods={"GET"})
     */
    public function lieux_ville(Villes $ville, VillesRepository $vr, LieuxRepository $lr, NormalizerInterface $ni): Response
    {
        $lieux = $lr->findBy(["noVille" => $ville->getId()]);
        $normalize = $ni->normalize($lieux, null, ["groups" => "lieu"]);
        return $this->json($normalize);
    }
    /**
     * @Route("/infos_lieux/{lieux}", name="infos_lieux", methods={"GET"})
     */
    public function infos_lieux(Lieux $lieux, LieuxRepository $lr, NormalizerInterface $ni): Response
    {
        $lieux = $lr->findBy(["id" => $lieux->getId()]);
        $normalize = $ni->normalize($lieux, null, ["groups" => "lieu"]);
        return $this->json($normalize);
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

    /**
     * @Route("/sortie/desister/{id}", name="sortie_se_desister")
     */
    public function seDesister(Sorties $sortie,Inscriptions $inscriptions,  Request $req, EntityManagerInterface $em): Response
    {

        $em->remove($inscriptions);
        $em->flush();

        return $this->redirectToRoute('accueil');
    }

}
