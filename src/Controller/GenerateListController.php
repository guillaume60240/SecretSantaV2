<?php

namespace App\Controller;

use App\Repository\SantaListRepository;
use App\Services\GenerateList;
use App\Services\SantaListService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenerateListController extends AbstractController
{
    #[Route('/generate/list/{listId}', name: 'generate_list')]
    public function index($listId, SantaListRepository $santaListRepository, SantaListService $santaListService): Response
    {
        $activeList = $santaListRepository->findOneBy(['id' => $listId]);
        if(!$this->getUser() || $activeList->getUserRelation() !== $this->getUser()) {
            return $this->redirectToRoute('home');
        }
        $activeList = $santaListService->getSantaListWithSantas($activeList);

        return $this->render('generate_list/index.html.twig', [
            'activeList' => $activeList
        ]);
    }

    #[Route('/generate/list/{listId}/generate', name: 'generate_list_generate')]
    public function generate($listId, GenerateList $generateList, SantaListRepository $santaListRepository): Response
    {
        $activeList = $santaListRepository->findOneBy(['id' => $listId]);
        if(!$this->getUser() || $activeList->getUserRelation() !== $this->getUser()) {
            return $this->redirectToRoute('home');
        }
        $generation = $generateList->initialiseGeneration($activeList);

        if($generation === true) {
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
