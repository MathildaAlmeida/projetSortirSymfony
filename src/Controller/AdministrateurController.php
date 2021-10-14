<?php

namespace App\Controller;

use App\Entity\Sites;
use App\Entity\Villes;
use App\Repository\SitesRepository;
use App\Repository\VillesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\All;

class AdministrateurController extends AbstractController
{
    /**
     * @Route("/admin", name="administrateur")
     */
    public function index(): Response
    {
        return $this->render('administrateur/accueil.html.twig', [
            'controller_name' => 'AdministrateurController',
        ]);
    }

    /**
     * @Route("/admin/villes", name="villes")
     */
    public function ville(VillesRepository $villeRepo, Request $request, EntityManagerInterface $em): Response
    {

        $ville = $villeRepo->findAll();

        $valeur=$request->get('valeur');
        
        $villeRechercher = $villeRepo->findBy(['nomVille' => $valeur]);


        return $this->render('administrateur/villes.html.twig', [
           'villes' => $ville,
           'valeur' => $valeur,
            'villeFiltre' => $villeRechercher
        ]);
    }

    /**
     * @Route("/admin/sites", name="sites")
     */
    public function site(SitesRepository $sitesRepository): Response
    {
        $site = $sitesRepository->findAll();
        return $this->render('administrateur/sites.html.twig', [
           'sites'=>$site
        ]);
    }

    /**
     * @Route("admin/ville/ajouter", name="ajout_rapide_ville")
     */
    public function ajouteRapideVille(Request $request, EntityManagerInterface $em): Response
    {

        //Recuperer données formulaire
        $nomVille = $request->get('nomVille');
        $codePostal = $request->get('codePostal');
        $ville = new Villes();
        $ville->setNomVille($nomVille);
        $ville->setCodePostal($codePostal);
        $em->persist($ville);
        $em->flush();

        return $this->redirectToRoute('villes');
    }

    /**
     * @Route("admin/site/ajouter", name="ajout_rapide_site")
     */
    public function ajouteRapideSite(Request $request, EntityManagerInterface $em): Response
    {

        //Recuperer données formulaire
        $nomSite = $request->get('nomSite');
        $site = new Sites();
        $site->setNomSite($nomSite);
        $em->persist($site);
        $em->flush();

        return $this->redirectToRoute('sites');
    }

    /**
     * @Route("admin/ville/modifier/{id}", name="modifier_ville")
     */
    public function modifierVille(Villes $ville, EntityManagerInterface $em,Request $request ): Response
    {
        //Recuperer données formulaire
        $nomVille = $request->get('nomVille2');
        $codePostal = $request->get('codePostal2');
        $ville->setNomVille($nomVille);
        $ville->setCodePostal($codePostal);
        $em->persist($ville);
        $em->flush();

        return $this->redirectToRoute('villes');
    }

     /**
     * @Route("admin/site/modifier/{id}", name="modifier_site")
     */
    public function modifierSite(Sites $site, EntityManagerInterface $em , Request $request): Response
    {
        
        //Recuperer données formulaire
        $nomSite = $request->get('nomSite2');
        $site->setNomSite($nomSite);
        $em->persist($site);
        $em->flush();

        return $this->redirectToRoute('sites');
    }

    /**
     * @Route("admin/ville/supprimer/{id}", name="supprimer_ville")
     */
    public function supprimerVille(Villes $ville, EntityManagerInterface $em ): Response
    {
        $em->remove($ville);
        $em->flush();

        return $this->redirectToRoute('villes');
    }

     /**
     * @Route("admin/site/supprimer/{id}", name="supprimer_site")
     */
    public function supprimerSite(Sites $site, EntityManagerInterface $em ): Response
    {
        $em->remove($site);
        $em->flush();

        return $this->redirectToRoute('sites');
    }
}
