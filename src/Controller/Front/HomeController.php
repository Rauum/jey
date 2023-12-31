<?php

namespace App\Controller\Front;

use App\Repository\ProductRepository;
use App\Repository\SportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_front_home')]
    public function home(ProductRepository $productRepository, SportRepository $sportRepository): Response
    {
        return $this->render('front/home.html.twig', [
            'products' => $productRepository->findBy([],['createdAt'=>'DESC']),
            'lastProducts' => $productRepository->findBy([],['createdAt'=>'DESC'], 5),
            'sports' => $sportRepository->findBy(['displayMenu'=>1],['title'=>'ASC'])
        ]);
    }

}