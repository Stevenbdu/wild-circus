<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function contact(Request $request, MailerInterface $mailer)
    {
        $form = $this->createForm(ContactType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $contactFormData = $form->getData();
            $message = (new Email())
                ->from($this->getParameter('mailer_from'))
                ->to($contactFormData['email'])
                ->subject('Question')
                ->html($this->renderView('contact/mail.html.twig', [
                    'message' => $contactFormData['message']
                ]));
            $mailer->send($message);
            $this->addFlash(
                'success',
                "Votre demande a bien été pris en compte.
                 "
            );

            return $this->redirectToRoute("home_index");
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
