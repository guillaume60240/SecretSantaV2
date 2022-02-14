<?php

namespace App\Controller;

use App\Repository\SantaListRepository;
use App\Repository\SantaRepository;
use App\Services\HandleRegisterForm;
use App\Services\SantaListService;
use App\Services\SantasService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SantaController extends AbstractController
{
    public function __construct(SantaListRepository $santaListRepository, SantaRepository $santaRepository, SantasService $santasService, SantaListService $santaListService, HandleRegisterForm $handleRegisterForm)
    {
        $this->santaListRepository = $santaListRepository;
        $this->santaRepository = $santaRepository;
        $this->santasService = $santasService;
        $this->santaListService = $santaListService;
        $this->handleRegisterForm = $handleRegisterForm;
    }
    
    #[Route('/santa/{id}', name: 'add_santa')]
    public function add(int $id, Request $request): Response
    {
        $activeList = $this->santaListRepository->findOneBy(['id' => $id]);
        if( !$activeList || $activeList->getUserRelation() !== $this->getUser() ) {
            return $this->redirectToRoute('account');
        }

        if($request->isMethod('POST')) {
            $form = $request->request->all();
            if(isset($form['submitAddSanta'])){
                $submission = $this->handleRegisterForm->addSanta($form, $activeList);
                if ($submission === true) {
                    $this->addFlash('success', 'Votre santa a bien été ajouté');
                    return $this->redirectToRoute('account_list_details', ['id' => $id]);
                } else {
                    $this->addFlash('error', $submission);
                }
            }
        }

        return $this->render('santa/index.html.twig', [
        ]);
    }

    #[Route('/santa/{id}/{santaId}', name: 'remove_santa')]
    public function remove(int $id, int $santaId): Response
    {
        $activeList = $this->santaListRepository->findOneBy(['id' => $id]);
        $santaToRemove = $this->santaRepository->findOneBy(['id' => $santaId]);
        if( !$activeList || $activeList->getUserRelation() !== $this->getUser() || !$santaToRemove || $santaToRemove->getSantaListRelation() !== $activeList ) {
            return $this->redirectToRoute('account');
        }

        $action = $this->santasService->removeSanta($santaToRemove);
        if ($action === true) {
            $this->addFlash('success', 'Votre santa a bien été supprimé');
        } else {
            $this->addFlash('error', "Une erreur est survenue lors de la suppression de votre santa");
        }

        return $this->redirectToRoute('account_list_details', ['id' => $id]);
    }

    #[Route('/santa/{listId}/{santaId}/manage-constraints', name: 'manage_constraints')]
    public function manageConstraints(int $listId, int $santaId, Request $request): Response
    {
        $activeList = $this->santaListRepository->findOneBy(['id' => $listId]);
        $santaToManage = $this->santaRepository->findOneBy(['id' => $santaId]);
        if( !$activeList || $activeList->getUserRelation() !== $this->getUser() || !$santaToManage || $santaToManage->getSantaListRelation() !== $activeList ) {
            return $this->redirectToRoute('account');
        }
        $list = $this->santaListService->getSantaListWithSantas($activeList);

        $constraints = $santaToManage->getCantGiveGift();
        $allConstraints[] = null;
        if($constraints){
            foreach ($constraints as $constraint) {
                $allConstraints[] = $constraint->getId();
            }
        } else {
            $allConstraints = null;
        }

        if($request->isMethod('POST')) {
            $form = $request->request->all();
            if(isset($form['submitConstraints'])){
                $submission = $this->handleRegisterForm->manageConstraints($form, $this->getUser(), $activeList, $santaToManage);
                if ($submission === true) {
                    $this->addFlash('success', 'Vos contraintes ont bien été modifiées');
                    return $this->redirectToRoute('account_list_details', ['id' => $listId]);
                } else {
                    $this->addFlash('error', $submission);
                }
            }
        }
        
        return $this->render('account/manageConstraints.html.twig', [
            'santa' => $santaToManage,
            'list' => $list,
            'constraints' => $constraints,
            'allConstraints' => $allConstraints,
        ]);
    }
}
