<?php

namespace App\Controller;

use App\Entity\Tour;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mime\Email;

class ReservationController extends AbstractController
{

    /**
     * @Route("/reservations", name="reservations")
     */
    public function reservations()
    {
        $user = $this->getUser();
        return $this->render('reservation/reservation.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("reserve/{id}", name="reserve")
     */
    public function reserve(Tour $tour, MailerInterface $mailer): Response
    {
        $user = $this->getUser();
        $tour->addReservation($user);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($tour);
        $entityManager->flush();
        $message = (new Email())
            ->from($this->getParameter('mailer_from'))
            ->to($user->getEmail())
            ->subject('Réservation')
            ->html($this->renderView('contact/mail_reservation.html.twig', [
                'tour' => $tour
            ]));
        $mailer->send($message);
        $this->addFlash(
            'success',
            "Demande de réservation effectué, un mail va vous être envoyé."
        );

        return $this->redirectToRoute('tour_index_public');

    }

    /**
     * @Route("/cancel/{id}", name="cancel_reservation")
     */
    public function cancelReservation(Tour $tour): Response
    {
        $user = $this->getUser();
        $tour->removeReservation($user);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($tour);
        $entityManager->flush();
        $this->addFlash(
            'success',
            "Réservation annulé"
        );

        return $this->redirectToRoute('reservations');


    }

}
