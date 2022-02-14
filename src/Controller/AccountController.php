<?php

namespace App\Controller;

use App\Repository\SantaListRepository;
use App\Services\HandleRegisterForm;
use App\Services\SantaListService;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    public function __construct(UserService $userService, SantaListService $santaListService, SantaListRepository $santaListRepository, HandleRegisterForm $handleRegisterForm) 
    {
        $this->userService = $userService;
        $this->santaListService = $santaListService;
        $this->santaListRepository = $santaListRepository;
        $this->handleRegisterForm = $handleRegisterForm;
    }

    #[Route('/compte', name: 'account')]
    public function index(): Response
    {   
        $this->userService->getUserWithListsAndSantas($this->getUser());
        
        return $this->render('account/index.html.twig');
    }

    #[Route('/compte/details-liste/{id}', name: 'account_list_details')]
    public function lists(int $id, Request $request): Response
    {   
        $list = $this->santaListRepository->findOneBy(['id' => $id]);
        if(!$list || $list->getUserRelation()->getUserIdentifier() !== $this->getUser()->getUserIdentifier()){
            return $this->redirectToRoute('account');
        } 
        $list = $this->santaListService->getSantaListWithSantas($list);

        if($request->isMethod('POST')){
           $form = $request->request->all();
            if(isset($form['updateSantaName'])){
                $action = $this->handleRegisterForm->updateSantaName($form, $list, $this->getUser());
                if($action === true){
                    $this->addFlash('success', 'Le nom du santa a bien été modifié');
                    return $this->redirectToRoute('account_list_details', ['id' => $id]);
                } else {
                    $this->addFlash('error', $action);
                }
            } elseif(isset($form['updateSantaMail'])){
                $action = $this->handleRegisterForm->updateSantaMail($form, $list, $this->getUser());
                if($action === true){
                    $this->addFlash('success', 'Le mail du santa a bien été modifié');
                    return $this->redirectToRoute('account_list_details', ['id' => $id]);
                } else {
                    $this->addFlash('error', $action);
                }
            }
        }

        return $this->render('account/listDetails.html.twig', [
            'list' => $list,
        ]);
    }

    #[Route('/compte/update-liste/{id}', name: 'list_update')]
    public function updateList(int $id, Request $request): Response
    {   
        $list = $this->santaListRepository->findOneBy(['id' => $id]);
        if(!$list || $list->getUserRelation()->getUserIdentifier() !== $this->getUser()->getUserIdentifier()){
            return $this->redirectToRoute('account');
        } 
        $list = $this->santaListService->getSantaListWithSantas($list);
       
        if($request->isMethod('POST')){
            $form = $request->request->all();
            if(isset($form['updateList'])){
                $action = $this->handleRegisterForm->updateList($form, $list, $this->getUser());
                if($action === true){
                    $this->addFlash('success', 'La liste a bien été modifiée');
                    return $this->redirectToRoute('account_list_details', ['id' => $id]);
                } else {
                    $this->addFlash('error', $action);
                }
            }
        }
    
        return $this->render('account/updateList.html.twig', [
            'list' => $list,
        ]);
    }

    #[Route('/compte/create-liste', name: 'create_list')]
    public function createList(Request $request)
    {
        if($request->isMethod('POST')){
            $form = $request->request->all();
            if(isset($form['submitCreateList'])){
                $action = $this->handleRegisterForm->createList($form, $this->getUser());
                if($action === true){
                    $this->addFlash('success', 'La liste a bien été créée');
                    return $this->redirectToRoute('account');
                } else {
                    $this->addFlash('error', $action);
                }
            }
        }

        return $this->render('account/createList.html.twig');
    }

    #[Route('/compte/delete-list/{id}', name: 'delete_list')]
    public function deleteList(int $id)
    {
        $list = $this->santaListRepository->findOneBy(['id' => $id]);
        if(!$list || $list->getUserRelation()->getUserIdentifier() !== $this->getUser()->getUserIdentifier()){
            return $this->redirectToRoute('account');
        } 
        $this->santaListService->deleteList($list);
        $this->addFlash('success', 'La liste a bien été supprimée');
        return $this->redirectToRoute('account');
    }
}
