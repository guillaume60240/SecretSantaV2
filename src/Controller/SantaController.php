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
    #[Route('/santa/{id}', name: 'add_santa')]
    public function add(SantaListRepository $santaListRepository, Request $request, HandleRegisterForm $form, $id): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('home');
        }
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
        if(!$this->getUser()){
            return $this->redirectToRoute('home');
        }
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

    #[Route('/santa/{listId}/{santaId}/manage-constraints', name: 'manage_constraints')]
    public function manageConstraints($listId, $santaId, SantaListRepository $santaListRepository, SantaRepository $santaRepository, SantaListService $santaListService, Request $request, HandleRegisterForm $handleRegisterForm): Response
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('home');
        }
        $activeList = $santaListRepository->findOneBy(['id' => $listId]);
        $santaToManage = $santaRepository->findOneBy(['id' => $santaId]);
        if( !$activeList || $activeList->getUserRelation() !== $this->getUser() || !$santaToManage || $santaToManage->getSantaListRelation() !== $activeList ) {
            return $this->redirectToRoute('account');
        }
        $constraints = $santaToManage->getCantGiveGift();
        $allConstraints[] = null;
        if($constraints){
            
            foreach ($constraints as $constraint) {
                $allConstraints[] = $constraint->getId();
            }
        } else {
            $allConstraints = null;
        }
        if(isset($_POST['submitConstraints'])){
            // dd($request->request->all());
            $action = $handleRegisterForm->manageConstraints($request->request->all(), $this->getUser(), $activeList, $santaToManage);
            if ($action === true) {
                $this->addFlash('success', 'Vos contraintes ont bien été modifiées');
                return $this->redirectToRoute('account_list_details', ['id' => $listId]);
            } else {
                $this->addFlash('error', $action);
            }
        }

        $list = $santaListService->getSantaListWithSantas($activeList);
        // dd($list, $allConstraints, $constraints);
        return $this->render('account/manageConstraints.html.twig', [
            'santa' => $santaToManage,
            'list' => $list,
            'constraints' => $constraints,
            'allConstraints' => $allConstraints,
        ]);
    }
}
