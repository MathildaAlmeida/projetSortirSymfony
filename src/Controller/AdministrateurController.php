<?php

namespace App\Controller;

use App\Entity\Sites;
use App\Entity\Villes;
use App\Repository\SitesRepository;
use App\Repository\VillesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdministrateurController extends AbstractController
{
    /**
     * @Route("/administrateur", name="administrateur")
     */
    public function index(): Response
    {
        return $this->render('administrateur/accueil.html.twig', [
            'controller_name' => 'AdministrateurController',
        ]);
    }

    /**
     * @Route("/administrateur/villes", name="villes")
     */
    public function ville(VillesRepository $villeRepo): Response
    {

        $ville = $villeRepo->findAll();

        return $this->render('administrateur/villes.html.twig', [
           'villes' => $ville
        ]);
    }

    /**
     * @Route("/administrateur/sites", name="sites")
     */
    public function site(SitesRepository $sitesRepository): Response
    {
        $site = $sitesRepository->findAll();
        return $this->render('administrateur/sites.html.twig', [
           'sites'=>$site
        ]);
    }

    /**
     * @Route("/ville/supprimer/{id}", name="supprimer_ville")
     */
    public function supprimerVille(Villes $ville, EntityManagerInterface $em ): Response
    {
        $em->remove($ville);
        $em->flush();

        return $this->redirectToRoute('villes');
    }

     /**
     * @Route("/site/supprimer/{id}", name="supprimer_site")
     */
    public function supprimerSite(Sites $site, EntityManagerInterface $em ): Response
    {
        $em->remove($site);
        $em->flush();

        return $this->redirectToRoute('sites');
    }
}
