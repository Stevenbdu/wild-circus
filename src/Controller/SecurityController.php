<?php

namespace App\Controller;

use App\Entity\Tour;
use App\Form\ReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Form\TestType;

class SecurityController extends AbstractController
{
    /**
     * @Route("/reservations", name="reservations")
     */
    public function reservations()
    {
        $user = $this->getUser();
        return $this->render('security/reservation.html.twig', [
            'user' => $user,
        ]);
    }
    /**
     * @Route("reserve/{id}", name="reserve")
     */
    public function reserve(Tour $tour): Response
    {
        $user = $this->getUser();
        $tour->addReservation($user);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($tour);
        $entityManager->flush();

        return $this->redirectToRoute('home_index');

    }


    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }
}
