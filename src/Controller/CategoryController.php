<?php

namespace App\Controller;

use App\CustomFunctions;
use App\Entity\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CategoryController extends Controller
{
    /**
     * @Route("/categories", methods="GET")
     */
    public function index()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        $categoriesDetails = [];

        foreach ($categories as $c) {
            array_push($categoriesDetails, $c->getDetails());
        }

        return CustomFunctions::respondWithJSON($categoriesDetails);
    }

    /**
     * @Route("/categories/{id}", methods="GET")
     */
    public function show($id)
    {
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);

        if (!$category) {
            return CustomFunctions::respondWithJSON([
                'error' => 'No category found for id ' . $id
            ]);
        }

        return CustomFunctions::respondWithJSON($category->getDetails());
    }

    /**
     * @Route("/categories", methods="POST")
     */
    public function create(Request $rq)
    {
        $em = $this->getDoctrine()->getManager();

        $category = new Category();
        $category->setTitle($rq->get('title'));


        $em->persist($category);
        $em->flush();

        return CustomFunctions::respondWithJSON($category->getDetails());
    }

    /**
     * @Route("/categories", methods="PUT")
     */
    public function update(Request $rq)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository(Category::class)->find($rq->get('id'));

        if (!$category) {
            return CustomFunctions::respondWithJSON([
                'error' => 'No category found for id ' . $rq->get('id')
            ]);
        }

        if ($rq->get('title')) $category->setTitle($rq->get('title'));

        $em->flush();

        return CustomFunctions::respondWithJSON($category->getDetails());
    }

    /**
     * @Route("/categories/{id}", methods="DELETE")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $category = $em->getRepository(Category::class)->find($id);

        if (!$category) {
            return CustomFunctions::respondWithJSON([
                'error' => 'No category found for id ' . $id
            ]);
        }

        $em->remove($category);
        $em->flush();

        return CustomFunctions::respondWithJSON([
            'success' => 'Category was deleted'
        ]);
    }
}
