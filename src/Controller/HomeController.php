<?php

namespace App\Controller;

use App\Repository\TourRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home_index")
     */
    public function index(TourRepository $tourRepository)
    {
        return $this->render('home/index.html.twig', [
            'tours' => $tourRepository->findBy([], ['start' => 'DESC']),
        ]);
    }
}
