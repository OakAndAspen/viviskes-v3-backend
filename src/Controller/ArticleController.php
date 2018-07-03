<?php

namespace App\Controller;

use App\CustomFunctions;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ArticleController extends Controller
{
    /**
     * @Route("/articles", methods="GET")
     */
    public function index()
    {
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();

        $articlesDetails = [];

        foreach ($articles as $a) {
            array_push($articlesDetails, $a->getDetails());
        }

        return CustomFunctions::respondWithJSON($articlesDetails);
    }

    /**
     * @Route("/articles/{id}", methods="GET")
     */
    public function show($id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        if (!$article) {
            return CustomFunctions::respondWithJSON([
                'error' => 'No article found for id ' . $id
            ]);
        }

        return CustomFunctions::respondWithJSON($article->getDetails());
    }

    /**
     * @Route("/articles", methods="POST")
     */
    public function create(Request $rq)
    {
        $em = $this->getDoctrine()->getManager();

        $article = new Article();
        $article->setTitle($rq->get('title'));
        $article->setCreationDate(new \DateTime());
        $article->setLastUpdate(new \DateTime());
        $article->setContent($rq->get('content'));

        $em->persist($article);
        $em->flush();

        return CustomFunctions::respondWithJSON($article->getDetails());
    }

    /**
     * @Route("/articles", methods="PUT")
     */
    public function update(Request $rq)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository(Article::class)->find($rq->get('id'));

        if (!$article) {
            return CustomFunctions::respondWithJSON([
                'error' => 'No article found for id ' . $rq->get('id')
            ]);
        }

        if($rq->get("title")) $article->setTitle($rq->get('title'));
        $article->setLastUpdate(new \DateTime());
        if($rq->get("content")) $article->setContent($rq->get('content'));

        $em->flush();

        return CustomFunctions::respondWithJSON($article->getDetails());
    }

    /**
     * @Route("/articles/{id}", methods="DELETE")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository(Article::class)->find($id);

        if (!$article) {
            return CustomFunctions::respondWithJSON([
                'error' => 'No article found for id ' . $id
            ]);
        }

        $em->remove($article);
        $em->flush();

        return CustomFunctions::respondWithJSON([
            'success' => 'Article was deleted'
        ]);
    }
}
