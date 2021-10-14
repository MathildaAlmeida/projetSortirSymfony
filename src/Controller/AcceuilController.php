<?php

namespace App\Controller;

use App\Entity\Inscriptions;
use App\Entity\Sorties;
use App\Repository\EtatsRepository;
use App\Repository\InscriptionsRepository;
use App\Repository\SitesRepository;
use App\Repository\SortiesRepository;
use App\Repository\UserRepository;
use http\Client\Curl\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AcceuilController extends AbstractController
{
    /**
     * @Route("/", name="accueil")
     */
    public function accueil(UserRepository $userRepository,EtatsRepository $repoEtat,SortiesRepository $repoSortie, SitesRepository $repoSite, InscriptionsRepository  $repoInscrit): Response
    {
        
        $sorties    = $repoSortie->findAll();
        $sites      = $repoSite->findAll();
        $etat       = $repoEtat->findAll();
        $user       = $this->getUser();
        $inscrits = "";

       
        if (!empty($user)) {
            $inscrits    = $repoInscrit->findBy(
                [
                    'noParticipant' => $user->getId()
                ]
            );
        }
        
        
        return $this->render('accueil/accueil.html.twig', [
            'sorties'   =>  $sorties,
            'sites'     =>  $sites,
            'user'      =>  $user,
            'inscrits'  =>  $inscrits,
            'etat'      =>  $etat
        ]);
    }

}
