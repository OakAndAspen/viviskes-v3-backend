<?php

namespace App\Controller;

use App\CustomFunctions;
use App\Entity\Partner;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PartnerController extends Controller
{

    /**
     * @Route("/partners", methods="GET")
     */
    public function index()
    {
        $partners = $this->getDoctrine()->getRepository(Partner::class)->findAll();

        $partnersDetails = [];

        foreach ($partners as $p) {
            array_push($partnersDetails, $p->getDetails());
        }

        return CustomFunctions::respondWithJSON($partnersDetails);
    }

    /**
     * @Route("/partners/{id}", methods="GET")
     */
    public function show($id)
    {
        $partner = $this->getDoctrine()->getRepository(Partner::class)->find($id);

        if (!$partner) {
            return CustomFunctions::respondWithJSON([
                'error' => 'No partner found for id ' . $id
            ]);
        }

        return CustomFunctions::respondWithJSON($partner->getDetails());
    }

    /**
     * @Route("/partners", methods="POST")
     */
    public function create(Request $rq)
    {
        $em = $this->getDoctrine()->getManager();

        $partner = new Partner();
        $partner->setName($rq->get('name'));
        if($rq->get('link')) $partner->setLink($rq->get('link'));

        $em->persist($partner);
        $em->flush();

        return CustomFunctions::respondWithJSON($partner->getDetails());
    }

    /**
     * @Route("/partners", methods="PUT")
     */
    public function update(Request $rq)
    {
        $em = $this->getDoctrine()->getManager();
        $partner = $em->getRepository(Partner::class)->find($rq->get('id'));

        if (!$partner) {
            return CustomFunctions::respondWithJSON([
                'error' => 'No partner found for id ' . $rq->get('id')
            ]);
        }

        if ($rq->get('name')) $partner->setName($rq->get('name'));
        if ($rq->get('link')) $partner->setLink($rq->get('link'));

        $em->flush();

        return CustomFunctions::respondWithJSON($partner->getDetails());
    }

    /**
     * @Route("/partners/{id}", methods="DELETE")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $partner = $em->getRepository(Partner::class)->find($id);

        if (!$partner) {
            return CustomFunctions::respondWithJSON([
                'error' => 'No partner found for id ' . $id
            ]);
        }

        $em->remove($partner);
        $em->flush();

        return CustomFunctions::respondWithJSON([
            'success' => 'Partner was deleted'
        ]);
    }
}
