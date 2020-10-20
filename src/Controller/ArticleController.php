<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\User;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/articles", name="articles_index")
     */
    public function index(ArticleRepository $repo)
    {
        $articles = $repo->findby(array(), array('id' => 'DESC'));


        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }

    /**
     * Créer une annonce
     *
     *@Route("/article/nouveau", name="article_create")
     *
     *@return Response
     */
    public function create(Request $request, EntityManagerInterface $manager){
        $article = new Article();

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $article->setAuteur($this->getUser());
            $article->setDateCreation(new \DateTime('now'));

            $manager->persist($article);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre article a bien été publié !'
            );

          $article->getSlug();


            return $this->redirectToRoute('articles_index');
        }

        return $this->render('article/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher l'article
     *
     * @Route("/article/{slug}", name="article_show")
     *
     * @return Response
     */
    public function show($slug, ArticleRepository $repo) {
        //Récupére l'annonce correspondante
        $article = $repo->findOneBy(array('slug' => $slug));

        return $this->render('article/show.html.twig', [
            'article' => $article
        ]);
    }

}
