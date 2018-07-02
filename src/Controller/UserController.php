<?php

namespace App\Controller;

use App\CustomFunctions;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    /**
     * @Route("/users", methods="GET")
     */
    public function index()
    {
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();

        $usersDetails = [];

        foreach ($users as $u) {
            array_push($usersDetails, $u->getDetails());
        }

        return CustomFunctions::respondWithJSON($usersDetails);
    }

    /**
     * @Route("/users/{id}", methods="GET")
     */
    public function show($id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);

        return CustomFunctions::respondWithJSON($user->getDetails());
    }

    /**
     * @Route("/users", methods="POST")
     */
    public function create(Request $rq)
    {
        $em = $this->getDoctrine()->getManager();

        $user = new User();
        $user->setEmail($rq->get('email'));
        $user->setLastName($rq->get('lastName'));
        $user->setFirstName($rq->get('firstName'));
        $user->setPassword(password_hash($rq->get('password'), PASSWORD_DEFAULT));
        $user->setAdmin(0);
        $user->setStatus('active');
        if ($rq->get('celtName')) $user->getCeltName($rq->get('celtName'));

        $em->persist($user);
        $em->flush();

        return CustomFunctions::respondWithJSON($user->getDetails());
    }

    /**
     * @Route("/users", methods="PUT")
     */
    public function update(Request $rq)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($rq->get('id'));

        if (!$user) {
            return CustomFunctions::respondWithJSON([
                'error' => 'No user found for id ' . $rq->get('id')
            ]);
        }

        if ($rq->get('email')) $user->setEmail($rq->get('email'));
        if ($rq->get('lastName')) $user->setLastName($rq->get('lastName'));
        if ($rq->get('firstName')) $user->setFirstName($rq->get('firstName'));
        if ($rq->get('password')) $user->setPassword(password_hash($rq->get('password'), PASSWORD_DEFAULT));
        if ($rq->get('admin')) $user->setAdmin(0);
        if ($rq->get('status')) $user->setStatus('active');
        if ($rq->get('celtName')) $user->setCeltName($rq->get('celtName'));

        $em->flush();

        return CustomFunctions::respondWithJSON($user->getDetails());
    }

    /**
     * @Route("/users/{id}", methods="DELETE")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(User::class)->find($id);

        if (!$user) {
            return CustomFunctions::respondWithJSON([
                'error' => 'No user found for id ' . $id
            ]);
        }

        $em->remove($user);
        $em->flush();

        return CustomFunctions::respondWithJSON([
            'success' => 'User was deleted'
        ]);
    }
}
