<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\InscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class GlobalController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('global/index.html.twig');
    }


    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $util)
    {
        return $this->render('global/login.html.twig',[
            "lastUsername" => $util->getLastUsername(),
            "error" => $util->getLastAuthenticationError()
        ]);

    }

     /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        $utilisateur = new Utilisateur();

        $form = $this->createForm(InscriptionType::class, $utilisateur);

        $form -> handleRequest($request);
        //dd($form);

        if ($form->isSubmitted() && $form->isValid() ) {
            $passwordCrypte = $encoder->encodePassword($utilisateur, $utilisateur->getPassword());
            $utilisateur->setPassword($passwordCrypte);
            $utilisateur->setRoles("ROLE_USER");
            $em->persist($utilisateur);
            $em->flush();
            $this->addFlash("success", "l'inscription a été éffectuer ");
            return $this->redirectToRoute("home");
        }

        return $this->render('global/inscription.html.twig', [
            'form' => $form->createView(),
        ]);
    }






}
