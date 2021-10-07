<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ProfilFormType;
use App\Security\LoginFormAuthentificatorAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use function PHPUnit\Framework\isEmpty;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="profil",  methods={"GET","POST"})
     */
    public function profil(Request $request, EntityManagerInterface  $em, UserPasswordEncoderInterface $passwordEncoder, ValidatorInterface $validator): Response
    {
        //dd($this->getUser());
        $userCon = $this->getUser();
        

        $user =new User();
        $form = $this->createForm(ProfilFormType::class, $userCon);
        $form->handleRequest($request);

        $errors = $validator->validate($user);

        if ($form->isSubmitted() && $form->isValid()) {
            if($user->getTelephone() == null){
                $user->setTelephone(null);
            }
            $user->setPassword( $passwordEncoder->encodePassword( $user,$user->getPassword() ));

            $user->setAdministrateur(0);
            $user->setActif(1);
            
            $em->persist($user);
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
        $user =new User();
        $form = $this->createForm(ProfilFormType::class, $user);
        $form->handleRequest($request);

        $errors = $validator->validate($user);

        if ($form->isSubmitted() && $form->isValid()) {
            if($user->getTelephone() == null){
                $user->setTelephone(null);
            }
            $user->setPassword( $passwordEncoder->encodePassword( $user,$user->getPassword() ));

            $user->setAdministrateur(0);
            $user->setActif(1);
            
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('accueil');
        }

        return $this->renderForm('profil/inscription.html.twig', [
            'form' => $form,
            'errors' => $errors
        ]);
    }
}
