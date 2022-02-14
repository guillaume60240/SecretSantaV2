<?php

namespace App\Controller;

use App\Repository\SantaListRepository;
use App\Services\GenerateList;
use App\Services\MailService;
use App\Services\SantaListService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenerateListController extends AbstractController
{
    public function __construct(SantaListService $santaListService, GenerateList $generateList, SantaListRepository $santaListRepository, MailService $mailService)
    {
        $this->santaListService = $santaListService;
        $this->generateList = $generateList;
        $this->santaListRepository = $santaListRepository;
        $this->mailService = $mailService;
    }
    
    #[Route('/generate/list/{listId}', name: 'generate_list')]
    public function index(int $listId): Response
    {
        $activeList = $this->santaListRepository->findOneBy(['id' => $listId]);
        if(!$activeList || $activeList->getUserRelation() !== $this->getUser()) {
            return $this->redirectToRoute('home');
        }
        $activeList = $this->santaListService->getSantaListWithSantas($activeList);

        return $this->render('generate_list/index.html.twig', [
            'activeList' => $activeList
        ]);
    }

    #[Route('/generate/list/{listId}/generate', name: 'generate_list_generate')]
    public function generate(int $listId): Response
    {
        $activeList = $this->santaListRepository->findOneBy(['id' => $listId]);
        if(!$activeList || $activeList->getUserRelation() !== $this->getUser()) {
            return $this->redirectToRoute('home');
        }
        $generation = $this->generateList->initialiseGeneration($activeList);

        if($generation === true) {
            if($activeList->getSendNotificationForGeneratedList() == true) {
                
                $this->mailService->sendListGenerationConfirm($this->getUser(), $activeList);
            }
            if($activeList->getSendMailToSantas() == true) {
                $this->mailService->sendSecretNameToSanta($this->getUser(), $activeList->getSantas(), $activeList);
            }
            $this->addFlash('success', 'La liste a bien été générée');
            return $this->redirectToRoute('account');
        } else {
            $this->addFlash('error', $generation);

        }
        
        return $this->render('generate_list/index.html.twig', [
            'activeList' => $activeList
        ]);
    }
}
