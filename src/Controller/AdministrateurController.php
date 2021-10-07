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
    public function ville(VillesRepository $villeRepo): Response
    {

        $ville = $villeRepo->findAll();

        return $this->render('administrateur/villes.html.twig', [
           'villes' => $ville
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
