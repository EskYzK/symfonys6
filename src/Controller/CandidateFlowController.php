<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Form\Candidate\CandidateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Flow\FormFlowInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CandidateFlowController extends AbstractController
{
    #[Route('/candidate_formflow', name: 'candidate_form_flow')]
    public function __invoke(Request $request): Response
    {
        /** @var FormFlowInterface $flow */
        $flow = $this->createForm(CandidateType::class, new Candidate())
            ->handleRequest($request);

        if ($flow->isSubmitted() && $flow->isValid() && $flow->isFinished()) {
            $this->addFlash('success', 'Your form flow was successfully finished!');

            return $this->redirectToRoute('candidate_form_flow');
        }

        return $this->render('candidate_flow/basic.html.twig', [
            'form' => $flow->getStepForm(),
        ]);
    }
}
