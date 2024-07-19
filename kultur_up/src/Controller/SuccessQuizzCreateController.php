<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SuccessQuizzCreateController extends AbstractController
{
    #[Route('/quizz-create/success', name: 'app_success_quizz_create')]
    public function index(): Response
    {
        return $this->render('success_quizz_create/index.html.twig', [
            'controller_name' => 'SuccessQuizzCreateController',
        ]);
    }
}
