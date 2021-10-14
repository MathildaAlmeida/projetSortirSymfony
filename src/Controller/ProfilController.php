<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfilFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="profil",  methods={"GET","POST"})
     */
    public function profil(Request $request, EntityManagerInterface  $em, UserPasswordEncoderInterface $passwordEncoder, ValidatorInterface $validator): Response
    {
        //dd($this->getUser());
        $userCon = $this->getUser();
        

       // $user =new User();
        $form = $this->createForm(ProfilFormType::class, $userCon);
        $form->handleRequest($request);

        $errors = $validator->validate($userCon);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $userCon->setPassword( $passwordEncoder->encodePassword( $userCon,$userCon->getPassword() ));

            $userCon->setAdministrateur(0);
            $userCon->setActif(1);
            
            $em->persist($userCon);
            $em->flush();

            return $this->redirectToRoute('accueil');
        }

        return $this->renderForm('profil/monProfil.html.twig', [
            'form' => $form,
            'errors' => $errors
        ]);
    }

    /**
     * @Route("/inscription", name="inscription",  methods={"GET","POST"})
     */
    public function inscription(Request $request, EntityManagerInterface  $em, UserPasswordEncoderInterface $passwordEncoder, ValidatorInterface $validator): Response
    {
        //Création de l'user
        $user =new User();

        //Création du formulaire d'inscription
        $form = $this->createForm(ProfilFormType::class, $user);
        $form->handleRequest($request);

        $errors = $validator->validate($user);
        //Verification si formulaire valide
        if ($form->isSubmitted() && $form->isValid()) {
            //Vérification si chmaps téléphone vide 
            if($user->getTelephone() == null){
                $user->setTelephone(null);
            }
            //Hashage du password pour inseré en base
            $user->setPassword( $passwordEncoder->encodePassword( $user,$user->getPassword() ));

            //Par défault, l'user n'est pas admin, et est actif
            $user->setAdministrateur(0);
            $user->setActif(1);
            
            //Insertion en base
            $em->persist($user);
            $em->flush();

            //Redirection page d'accueil si tout est ok
            return $this->redirectToRoute('accueil');
        }

        //Renvoi le formulaire sur la page d'inscription avec les potentiels erreurs du form
        return $this->renderForm('profil/inscription.html.twig', [
            'form' => $form,
            'errors' => $errors
        ]);
    }

    /**
     * @Route("/profilParticipant/{id}", name="profil_participant",  methods={"GET","POST"})
     */
    public function profilParticipant(User $participant): Response
    {

        return $this->renderForm('profil/profilParticipant.html.twig', [
            'participant' => $participant,
        ]);
    }
}
