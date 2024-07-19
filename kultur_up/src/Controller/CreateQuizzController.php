<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Question;
use App\Entity\Reponse;
use App\Form\CreateQuizzFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;

class CreateQuizzController extends AbstractController
{
    #[Route('/user/create-quizz', name: 'app_create_quizz')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {

        $categorie = new Categorie();
        for ($i = 0; $i < 10; $i++) {
            $question = new Question();
            $question->setQuestion('Question ' . ($i + 1));
            
            for ($j = 0; $j < 3; $j++) {
                $response = new Reponse();
                $response->setReponse('Response ' . ($j + 1));
                $response->setReponseExpected(false);
                $question->addReponse($response);
            }
            
            $categorie->addQuestion($question);
        }
    
        $form = $this->createForm(CreateQuizzFormType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('app_success_quizz_create');
        }

        return $this->render('create_quizz/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
