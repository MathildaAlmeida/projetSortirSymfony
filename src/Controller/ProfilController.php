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

use function PHPUnit\Framework\isEmpty;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil", name="profil")
     */
    public function index(): Response
    {
        return $this->render('profil/Acceuil.html.twig', [
            'controller_name' => 'ProfilController',
        ]);
    }

    /**
     * @Route("/inscription", name="profil_inscription",  methods={"GET","POST"})
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

            return $this->redirectToRoute('acceuil');
        }

        return $this->renderForm('profil/inscription.html.twig', [
            'form' => $form,
            'errors' => $errors
        ]);
    }
}
