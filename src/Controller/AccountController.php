<?php

namespace App\Controller;

use App\Form\UserProfileFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * @Route("/profile", name="user_profile")
     *
     * On peut limiter l'acces a une route (ou un controlleur)avec IsGRanted + son use ligne 5
     *@IsGranted("ROLE_USER")
     */
    public function index(Request $request, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder)
    {
        //sauvegarde de l'adresse email en cas d'erreur
        $email = $this->getUser()->getEmail();

        //On peut récuperer l'utilisateur actuellement conecté avec $this->getUser()
        $form =$this->createForm(UserProfileFormType::class, $this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            //Récuperaion du champ plainPassword
            $password = $form->get('plainPassword')->getData();

           //On met a jour le mot de passe seulement si le champ a été remplie
            if($password !== null){
                $hash = $encoder->encodePassword($this->getUser(),$password);
                $this->getUser()->setPassword($hash);
            }

            $em->flush();
            $this->addFlash('success','vos information son a jour.');

        }else{
            /*
             * on remet l'adresse email originale de l'utilisateur
             * pour éviter qu'il soit déconnecté
             */
            $this->getUser()->setEmail($email);
        }

        //sauvgarde de l'adresse email

        return $this->render('account/index.html.twig', [
            'profile_form'=>$form->createView(),
        ]);
    }
}
