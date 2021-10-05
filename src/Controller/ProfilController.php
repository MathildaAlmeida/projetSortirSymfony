<?php

namespace App\Controller;

use App\Entity\Participants;
use App\Form\ProfilFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="profil")
     */
    public function index(): Response
    {
        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }

    /**
     * @Route("/inscription", name="profil_inscription",  methods={"GET","POST"})
     */
    public function inscription(Request $request): Response
    {
        
        $form = $this->createForm(ProfilFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('acceuil');
        }

        return $this->renderForm('profil/inscription.html.twig', [
            'form' => $form
        ]);
    }
}
