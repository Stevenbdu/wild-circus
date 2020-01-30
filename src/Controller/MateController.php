<?php

namespace App\Controller;

use App\Entity\Mate;
use App\Form\MateType;
use App\Repository\MateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class MateController extends AbstractController
{
    /**
     * @Route("equipe", name="equipe_index", methods={"GET"})
     */
    public function indexEquipe(MateRepository $mateRepository): Response
    {
        return $this->render('mate/index_equipe.html.twig', [
            'mates' => $mateRepository->findAll(),
        ]);
    }
    /**
     * @Route("mate", name="mate_index", methods={"GET"})
     */
    public function index(MateRepository $mateRepository): Response
    {
        return $this->render('mate/index.html.twig', [
            'mates' => $mateRepository->findAll(),
        ]);
    }

    /**
     * @Route("mate/new", name="mate_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $mate = new Mate();
        $form = $this->createForm(MateType::class, $mate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mate);
            $entityManager->flush();

            return $this->redirectToRoute('mate_index');
        }

        return $this->render('mate/new.html.twig', [
            'mate' => $mate,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("mate/{id}", name="mate_show", methods={"GET"})
     */
    public function show(Mate $mate): Response
    {
        return $this->render('mate/show.html.twig', [
            'mate' => $mate,
        ]);
    }

    /**
     * @Route("mate/{id}/edit", name="mate_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Mate $mate): Response
    {
        $form = $this->createForm(MateType::class, $mate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $mate->setPicture("assets/build/sylvain.jpg");
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('mate_index');
        }

        return $this->render('mate/edit.html.twig', [
            'mate' => $mate,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("mate/{id}", name="mate_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Mate $mate): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mate->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('mate_index');
    }
}
