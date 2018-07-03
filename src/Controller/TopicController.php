<?php

namespace App\Controller;

use App\CustomFunctions;
use App\Entity\Category;
use App\Entity\Topic;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TopicController extends Controller
{
    /**
     * @Route("/topics", methods="GET")
     */
    public function index()
    {
        $topics = $this->getDoctrine()->getRepository(Topic::class)->findAll();

        $topicsDetails = [];

        foreach ($topics as $t) {
            array_push($topicsDetails, $t->getDetails());
        }

        return CustomFunctions::respondWithJSON($topicsDetails);
    }

    /**
     * @Route("/topics/{id}", methods="GET")
     */
    public function show($id)
    {
        $topic = $this->getDoctrine()->getRepository(Topic::class)->find($id);

        if (!$topic) {
            return CustomFunctions::respondWithJSON([
                'error' => 'No topic found for id ' . $id
            ]);
        }

        return CustomFunctions::respondWithJSON($topic->getDetails());
    }

    /**
     * @Route("/topics", methods="POST")
     */
    public function create(Request $rq)
    {
        $em = $this->getDoctrine()->getManager();

        $topic = new Topic();

        $user = $em->getRepository(User::class)->find($rq->get('user'));
        if (!$user) {
            return CustomFunctions::respondWithJSON([
                'error' => 'No user found for id ' . $rq->get('user')
            ]);
        }

        $category = $em->getRepository(Category::class)->find($rq->get('category'));
        if (!$category) {
            return CustomFunctions::respondWithJSON([
                'error' => 'No category found for id ' . $rq->get('category')
            ]);
        }

        $topic->setUser($user);
        $topic->setCategory($category);
        $topic->setTitle($rq->get('title'));
        $topic->setCreationDate(new \DateTime());
        $topic->setPinned($rq->get('pinned'));

        $em->persist($topic);
        $em->flush();

        return CustomFunctions::respondWithJSON($topic->getDetails());
    }

    /**
     * @Route("/topics", methods="PUT")
     */
    public function update(Request $rq)
    {
        $em = $this->getDoctrine()->getManager();
        $topic = $em->getRepository(Topic::class)->find($rq->get('id'));

        if (!$topic) {
            return CustomFunctions::respondWithJSON([
                'error' => 'No topic found for id ' . $rq->get('id')
            ]);
        }

        if ($rq->get('category')) {
            $category = $em->getRepository(Category::class)->find($rq->get('category'));
            $topic->setCategory($category);
        }

        if ($rq->get('title')) $topic->setTitle($rq->get('title'));
        if ($rq->get('pinned')) $topic->setPinned($rq->get('pinned'));

        $em->flush();

        return CustomFunctions::respondWithJSON($topic->getDetails());
    }

    /**
     * @Route("/topics/{id}", methods="DELETE")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $topic = $em->getRepository(Topic::class)->find($id);

        if (!$topic) {
            return CustomFunctions::respondWithJSON([
                'error' => 'No topic found for id ' . $id
            ]);
        }

        $em->remove($topic);
        $em->flush();

        return CustomFunctions::respondWithJSON([
            'success' => 'Topic was deleted'
        ]);
    }
}
