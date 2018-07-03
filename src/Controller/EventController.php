<?php

namespace App\Controller;

use App\CustomFunctions;
use App\Entity\Event;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class EventController extends Controller
{
    /**
     * @Route("/events", methods="GET")
     */
    public function index()
    {
        $events = $this->getDoctrine()->getRepository(Event::class)->findAll();

        $eventsDetails = [];

        foreach ($events as $e) {
            array_push($eventsDetails, $e->getDetails());
        }

        return CustomFunctions::respondWithJSON($eventsDetails);
    }

    /**
     * @Route("/events/{id}", methods="GET")
     */
    public function show($id)
    {
        $event = $this->getDoctrine()->getRepository(Event::class)->find($id);

        if (!$event) {
            return CustomFunctions::respondWithJSON([
                'error' => 'No event found for id ' . $id
            ]);
        }

        return CustomFunctions::respondWithJSON($event->getDetails());
    }

    /**
     * @Route("/events", methods="POST")
     */
    public function create(Request $rq)
    {
        $em = $this->getDoctrine()->getManager();

        $event = new Event();
        $event->setTitle($rq->get('title'));
        if ($rq->get('location')) $event->setLocation($rq->get('location'));
        $event->setPublic($rq->get('public'));
        if ($rq->get('startDate')) $event->setStartDate($rq->get('startDate'));
        if ($rq->get('endStart')) $event->setEndDate($rq->get('endStart'));
        $event->setCode(CustomFunctions::normalize($rq->get('title')));
        if ($rq->get('description')) $event->setDescription($rq->get('description'));

        $em->persist($event);
        $em->flush();

        return CustomFunctions::respondWithJSON($event->getDetails());
    }

    /**
     * @Route("/events", methods="PUT")
     */
    public function update(Request $rq)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository(Event::class)->find($rq->get('id'));

        if (!$event) {
            return CustomFunctions::respondWithJSON([
                'error' => 'No event found for id ' . $rq->get('id')
            ]);
        }

        if ($rq->get('title')) $event->setTitle($rq->get('title'));
        if ($rq->get('location')) $event->setLocation($rq->get('location'));
        if ($rq->get('public')) $event->setPublic($rq->get('public'));
        if ($rq->get('startDate')) $event->setStartDate($rq->get('startDate'));
        if ($rq->get('endStart')) $event->setEndDate($rq->get('endStart'));
        if ($rq->get('code')) $event->setCode($rq->get('code'));
        if ($rq->get('description')) $event->setDescription($rq->get('description'));

        $em->flush();

        return CustomFunctions::respondWithJSON($event->getDetails());
    }

    /**
     * @Route("/events/{id}", methods="DELETE")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $event = $em->getRepository(Event::class)->find($id);

        if (!$event) {
            return CustomFunctions::respondWithJSON([
                'error' => 'No event found for id ' . $id
            ]);
        }

        $em->remove($event);
        $em->flush();

        return CustomFunctions::respondWithJSON([
            'success' => 'Event was deleted'
        ]);
    }
}
