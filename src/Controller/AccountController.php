<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Form\RegistrationType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AccountController extends AbstractController
{
    /**
     * Affichage et gestion des connexions
     * @Route("/login", name="account_login")
     *
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * Deconnexion
     *
     * @Route("/logout", name="account_logout")
     * @return void
     */
    public function logout()
    {
        //
    }

    /**
     * Affiche le formulaire d'inscription
     *
     * @Route ("/inscription", name="account_register")
     * @param Request $request
     * @param ObjectManager $manager
     * @return Response
     */
    public function register(Request $request, EntityManagerInterface $manager, UserPasswordEncoderInterface $encoder) {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setPassword($hash);

            $user->setDroit(3);
            $user->setActif(0);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre compte a bien été crée !'
            );

            return $this->redirectToRoute('account_login');
        }

        return $this->render('account/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Affichage et traite le formulaire de mofication de profil
     *
     * @Route("/account/profile", name="account_profile")
     *
     * @return Response
     */
    public function profil(Request $request, EntityManagerInterface $manager) {
            $user = $this->getUser();
        if(!empty($user)) {


            $form = $this->createForm(AccountType::class, $user);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    'Les données ont été modifées !'
                );
            }

            return $this->render('account/profile.html.twig', [
                'form' => $form->createView()
            ]);
        } else {
            return $this->redirectToRoute('index');
        }
    }
}
