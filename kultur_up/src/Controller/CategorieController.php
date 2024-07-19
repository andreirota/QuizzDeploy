<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Repository\QuestionRepository;
use App\Repository\ReponseRepository;

class CategorieController extends AbstractController
{
    #[Route('/categorie/{id}', name: 'app_categorie')]
    public function index($id, QuestionRepository $questionRepository,  SessionInterface $session): Response
    {
        $session->set('points', 0);
        $questions = $questionRepository->findBy(['id_categorie' => $id]);
        
        if($questions){

          //  dd($id);
            return $this->redirectToRoute('app_categorie_question', [
                'categorieId' => $id,
                'questionId' => $questions[0]->getId(),   
            ]);
        }
        return $this->render('categorie/index.html.twig', [
            'questions' => $questions,
        ]);
    }


    #[Route('/categorie/{categorieId}/question/{questionId}', name: 'app_categorie_question')]
    public function question($categorieId, $questionId, Request $request, QuestionRepository $questionRepository, ReponseRepository $reponseRepository, SessionInterface $session ): Response
    {
        $question = $questionRepository->find($questionId);
        $reponses = $reponseRepository->findBy(['id_question' => $questionId]);
        //on mélange les réponses pour des raisons évidentes 
         shuffle($reponses);
        if ($request->isMethod('POST')){

            //on met de côté la réponse et on l'évalue
            $selectedAnswerId = $request->request->get('reponse');
            $selectedAnswer = $reponseRepository->find($selectedAnswerId);
            if($selectedAnswer->isReponseExpected()){
                $score = $session->get('points');
                $session->set('points', $score + 1);
            }
                //on passe à la question suivante
            $questions = $questionRepository->findBy(['id_categorie' => $categorieId]);
            $indexDeLaQuestionActuelle = array_search($question, $questions);
            if ($indexDeLaQuestionActuelle !== false && isset($questions[$indexDeLaQuestionActuelle + 1])){
                $prochaineQuestion = $questions[$indexDeLaQuestionActuelle + 1];
                return $this->redirectToRoute('app_categorie_question', [
                    'categorieId' => $categorieId,
                    'questionId' => $prochaineQuestion->getId()
                ]);

            }else {
                return $this->redirectToRoute('app_categorie_fin', ['categorieId' => $categorieId]);
            }
        }
        return $this->render('question/question.html.twig', [
            'question' => $question,
            'reponses' => $reponses,
        ]);
    }

    #[Route('categorie/{categorieId}/complete', name: 'app_categorie_fin')]
    public function fin($categorieId, SessionInterface $session): Response
    {
        $score = $session->get('points');
        return $this->render('fin/fin.html.twig', [
            'points' =>$score,
        ]);
    }


}
