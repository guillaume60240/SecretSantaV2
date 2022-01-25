<?php

namespace App\Controller;

use App\Repository\SantaListRepository;
use App\Repository\SantaRepository;
use App\Services\HandleRegisterForm;
use App\Services\SantasService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SantaController extends AbstractController
{
    #[Route('/santa/{id}', name: 'add_santa')]
    public function add(SantaListRepository $santaListRepository, Request $request, HandleRegisterForm $form, $id): Response
    {
        $activeList = $santaListRepository->findOneBy(['id' => $id]);
        if( !$activeList || $activeList->getUserRelation() !== $this->getUser() ) {
            return $this->redirectToRoute('account');
        }
        if(isset($_POST['submitAddSanta'])){
            $submission = $form->addSanta($request->request->all(), $activeList);
            // dd($request->request->all());
            if ($submission === true) {
                $this->addFlash('success', 'Votre santa a bien été ajouté');
                return $this->redirectToRoute('account_list_details', ['id' => $id]);
            } else {
                $this->addFlash('error', $submission);
            }
            
        }
        return $this->render('santa/index.html.twig', [
        ]);
    }

    #[Route('/santa/{id}/{santaId}', name: 'remove_santa')]
    public function remove(SantaListRepository $santaListRepository, SantaRepository $santaRepository, SantasService $santasService, $id, $santaId): Response
    {
        $activeList = $santaListRepository->findOneBy(['id' => $id]);
        $santaToRemove = $santaRepository->findOneBy(['id' => $santaId]);
        if( !$activeList || $activeList->getUserRelation() !== $this->getUser() || !$santaToRemove || $santaToRemove->getSantaListRelation() !== $activeList ) {
            return $this->redirectToRoute('account');
        }
        $action = $santasService->removeSanta($santaToRemove);
        if ($action === true) {
            $this->addFlash('success', 'Votre santa a bien été supprimé');
            return $this->redirectToRoute('account_list_details', ['id' => $id]);
        } else {
            $this->addFlash('error', "Une erreur est survenue lors de la suppression de votre santa");
            return $this->redirectToRoute('account_list_details', ['id' => $id]);
        }
    }
}
