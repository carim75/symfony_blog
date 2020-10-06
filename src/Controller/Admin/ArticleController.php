<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Form\ConfirmationType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ArticleController
 * @package App\Controller\Admin
 * @Route("/admin/article", name="admin_article_")
 */
class ArticleController extends AbstractController
{
    /**
     * @Route("/", name="list")
     */
    public function index(ArticleRepository $repository)
    {
        return $this->render('admin/article/index.html.twig', [
            'articles' => $repository->findAll(),
        ]);
    }

    /**
     * @Route ("/new", name="add")
     */
    public function add(Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(ArticleType::class);

        //handleRequest permet au formulaire de récuperer les données POSt et de procéder a la validation
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * getdata() permet de récupérer les données de formulaire
             * elle retourne par defaut un tableau des champ de formulaire
             * ou i retourne un objet de la classe a laquelle il est lié
             *
             */
            /**@var Article $article */
            $article = $form->getData();
            $em->persist($article);
            $em->flush();

            $this->addFlash('success', 'l\'articlea été créé');
            return $this->redirectToRoute('admin_article_edit', [
                'id' => $article->getID()
            ]);
        }
        return $this->render('admin/article/add.html.twig', [
            'article_form' => $form->createView()
        ]);
    }

    /**
     * @Route ("/{id}/edit", name="edit")
     */
    public function edit(Article $article, Request $request, EntityManagerInterface $em)
    {
        /**
         * On peut pré-remplir un formulaire en passant un 2eme argument a creatforme
         * on passe un tableau associatif ou un objet si le formulaire est lié à une classe
         */

        $form = $this->createForm(ArticleType::class, $article);

        //le formulaire va directement modifier l'objet
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * On a pas besoin d'appeler $form->getData()
             *     ->objet $article est directement modifié par le formulaire
             * on a pas besoin d'appeler $em-persist
             *     ->doctrine connait déja cet objet (il existe en base de données il sera automatiquement mis a jour
             */

            $em->flush();
            $this->addFlash('success', 'Article mis a jour.');
        }
        return $this->render('admin/article/edit.html.twig', [
            'article' => $article,
            'article_form' => $form->createView(),
        ]);
    }

    /**
     * @Route ("/{id}/delete", name="delete")
     */
    public function delete(Article $article, Request $request, EntityManagerInterface $am)
    {
        $form = $this->createForm(ConfirmationType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $am->remove( $article);
            $am->flush();

            /**
             * sprintf() sert a formater une chaine de caracteres
             * le %s est un emplacement pour une chaine de caratères
             */
            $this->addFlash('info', sprintf('L article "%s" à été supprimée.', $article->getTitle()));
            return $this->redirectToRoute('admin_article_list');
        }

        return $this->render('admin/article/delete.html.twig', [
            'article' => $article,
            'delete_form' =>$form->createView(),
        ]);

    }
    /**
     * @Route ("/{id}/publish/{token}", name="publish")
     * le paramètre token servira a verifier que l'action a bien été demandé par
     * l'administrateur connecté (protection contre les attaques CSRF)
     */
    public function publish(Article $article, string $token, EntityManagerInterface $em)
    {

        /**
         * On doit nommer le jetons crsf
         * symfony va comparer le jeton qu'il a enregistrer en sesion
         * avec se que l'on a récupéré  dans l'adresse
         */
        if ($this->isCsrfTokenValid('article-publish', $token) === false) {
            $this->addFlash('danger','le jeton est invalide.');
            return $this->redirectToRoute('admin_article_edit', [
                'id' => $article->getId(),
            ]);
        }

        $article->setPublishedAt(new \DateTime());
        $em->flush();

        $this->addFlash('success', 'l article a été publié');
        return $this->redirectToRoute('admin_article_edit', [
            'id' => $article->getId(),
        ]);
    }
}
