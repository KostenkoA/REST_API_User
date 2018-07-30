<?php

namespace App\Controller\Api;

use App\Entity\UserRegistrationLogin;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class UserRegistrationLoginController extends FOSRestController
{
    /**
     * @param int $id
     * @return View
     *
     * @Rest\Post("/login-registration/{id}")
     */
    public function loginRegistration(int $id): View
    {
        $loginRegistration = new UserRegistrationLogin();

        $loginRegistration->setUserId($id);
        $loginRegistration->setLastLoginAt(\date_create());

        $em = $this->getDoctrine()->getManager();
        $em->persist($loginRegistration);
        $em->flush();

        return $this->view($loginRegistration, Response::HTTP_CREATED);
    }

    /**
     * @return View
     *
     * @Rest\Get("/login-registration/{date}")
     */
    public function getAllUsersId($date)
    {
        $user = $this->getDoctrine()
            ->getRepository(UserRegistrationLogin::class)
            ->findAllUsersByDate($date);//TODO
        ;

        if (empty($user)){
            throw $this->createNotFoundException('Resource on that date -  not found!');
        }

        return $this->view($user, Response::HTTP_OK);
    }
}