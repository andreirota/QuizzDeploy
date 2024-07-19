<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CategorieRepository;

class HostPageController extends AbstractController
{
    #[Route('/', name: 'app_host_page')]
    public function index(CategorieRepository $categorieRepository): Response
    {

        $categories = $categorieRepository->findAll();
       // dd($categories);
        return $this->render('host_page/index.html.twig', [
            'categories' => $categories,
        ]);
    }
}
