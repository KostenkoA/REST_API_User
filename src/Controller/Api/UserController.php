<?php

namespace App\Controller\Api;

use App\Entity\User;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class UserController extends FOSRestController
{
    /**
     * @param Request $request
     * @return View
     *
     * @Rest\Post("/user")
     */
    public function postUser(Request $request): View
    {
        $post = $request->request;

        $user = new User();

        $user->setName($post->get('name'));
        $user->setLogin($post->get('login'));

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $this->view($user, Response::HTTP_CREATED);
    }

    /**
     * @param int $id
     * @return View
     *
     * @Rest\Get("/user/{id}")
     */
    public function getOneUser(int $id): View
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneById($id)
            ;

        if (empty($user)){
            throw $this->createNotFoundException('Resource with ID - '.$id.' not found!');
        }

        return $this->view($user, Response::HTTP_OK);
    }

    /**
     * @return View
     *
     * @Rest\Get("/users/")
     */
    public function getAllUsers(): View
    {
        /**
         * @var User $users
         */
        $users = $this->getDoctrine()
            ->getRepository(User::class)
            ->findAll()
            ;

        if (empty($users)){
            throw $this->createNotFoundException('No found any users!');
        }

        $response = [];
        /**
         * @var User $user
         */
        foreach ($users as $user){
            $response[] = [
                'data' => [
                    'type' => 'Users',
                    'id' => $user->getId(),
                    'attributes' => [
                        'name' => $user->getName(),
                        'login' => $user->getLogin(),
                    ],
                    'links' => [
                        'self' => \sprintf('/user/%d', $user->getId())
                    ],
                ],
            ];
        }

        return $this->view($response, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @param Request $request
     * @return View
     *
     * @Rest\Patch("/user/{id}")
     */
    public function patchUser(int $id, Request $request): View
    {
        /**
         * @var User $user
         */
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneById($id)
        ;

        if (empty($user)){
            throw $this->createNotFoundException('Resource with ID - '.$id.' not found!');
        }

        $post = $request->request;
        $user->setName($post->get('name'));
        $user->setLogin($post->get('login'));

        $me = $this->getDoctrine()->getManager();
        $me->persist($user);
        $me->flush();

        return $this->view($user, Response::HTTP_OK);
    }

    /**
     * @param int $id
     * @return View
     *
     * @Rest\Delete("/user/{id}")
     */
    public function deleteUser(int $id): View
    {
        /**
         * @var User $user
         */
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneById($id)
        ;

        if (empty($user)){
            throw $this->createNotFoundException('Resource with ID - '.$id.' not found!');
        }

        $me = $this->getDoctrine()->getManager();
        $me->remove($user);
        $me->flush();

        return $this->view([], Response::HTTP_NO_CONTENT);
    }

}