<?php

namespace App\Controller;

use App\Repository\SitesRepository;
use App\Repository\SortiesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AcceuilController extends AbstractController
{
    /**
     * @Route("/", name="acceuil")
     */
    public function index(SortiesRepository $repoSortie, SitesRepository $repoSite): Response
    {
        $sorties = $repoSortie->findAll();
        $sites = $repoSite->findAll();

        return $this->render('acceuil/Acceuil.html.twig', [
            'sorties' => $sorties,
            'sites'=> $sites
        ]);
    }
}
