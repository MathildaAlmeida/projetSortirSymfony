<?php

namespace App\Controller;

use App\Repository\InscriptionsRepository;
use App\Repository\SitesRepository;
use App\Repository\SortiesRepository;
use http\Client\Curl\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AcceuilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function accueil(SortiesRepository $repoSortie, SitesRepository $repoSite, InscriptionsRepository  $repoInscrit): Response
    {
        $sorties    = $repoSortie->findAll();
        $sites      = $repoSite->findAll();
        $user       = $this->getUser();
        $inscrits = "";
        if(!empty($user)){
            $inscrit    = $repoInscrit->findBy(
                [
                    'no_participant_id' => $user->getId()
                ]
            );
        }


        return $this->render('accueil/accueil.html.twig', [
            'sorties'   =>  $sorties,
            'sites'     =>  $sites,
            'user'      =>  $user,
            'inscrits'   =>  $inscrits
        ]);
    }
}
