<?php

namespace App\Controller;

use App\Entity\Sorties;
use App\Form\SortieType;
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
}
