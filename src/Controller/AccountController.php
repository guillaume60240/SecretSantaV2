<?php

namespace App\Controller;

use App\Repository\SantaListRepository;
use App\Repository\UserRepository;
use App\Services\HandleRegisterForm;
use App\Services\SantaListService;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/compte', name: 'account')]
    public function index(UserRepository $userRepository, UserService $userService): Response
    {   
        // $api_key = $_ENV['MAILJET_PUBLIC'];
        //  $api_key_secrete = $_ENV['MAILJET_SECRET'];
        //  dd($api_key, $api_key_secrete);
        if(!$this->getUser()){
            return $this->redirectToRoute('home');
        }
        $user = $userRepository->findOneBy(['email' => $this->getUser()->getUserIdentifier()]);
        
        if($user){
            $user = $userService->getUserWithListsAndSantas($user);
        } else {
            return $this->redirectToRoute('login');
        }
        // dd($user);
        return $this->render('account/index.html.twig', [
            
        ]);
    }

    #[Route('/compte/details-liste/{id}', name: 'account_list_details')]
    public function lists(SantaListRepository $santaListRepository, SantaListService $santaListService, HandleRegisterForm $handleRegisterForm, $id): Response
    {   
        if(!$this->getUser()){
            return $this->redirectToRoute('home');
        }
        $list = $santaListRepository->findOneBy(['id' => $id]);
        if(!$list || $list->getUserRelation()->getUserIdentifier() !== $this->getUser()->getUserIdentifier()){
            return $this->redirectToRoute('account');
        } 
        $list = $santaListService->getSantaListWithSantas($list);
        // dd($user);
        if(isset($_POST['updateSantaName'])){
            $action = $handleRegisterForm->updateSantaName($_POST, $list, $this->getUser());
            if($action === true){
                $this->addFlash('success', 'Le nom du santa a bien été modifié');
                return $this->redirectToRoute('account_list_details', ['id' => $id]);
            } else {
                $this->addFlash('error', $action);
            }
        }
        if(isset($_POST['updateSantaMail'])){
            $action = $handleRegisterForm->updateSantaMail($_POST, $list, $this->getUser());
            if($action === true){
                $this->addFlash('success', 'Le mail du santa a bien été modifié');
                return $this->redirectToRoute('account_list_details', ['id' => $id]);
            } else {
                $this->addFlash('error', $action);
            }
        }
        return $this->render('account/listDetails.html.twig', [
            'list' => $list,
        ]);
    }

    #[Route('/compte/update-liste/{id}', name: 'list_update')]
    public function updateList(SantaListRepository $santaListRepository, SantaListService $santaListService, HandleRegisterForm $handleRegisterForm, $id): Response
    {   
        if(!$this->getUser()){
            return $this->redirectToRoute('home');
        }
        $list = $santaListRepository->findOneBy(['id' => $id]);
        if(!$list || $list->getUserRelation()->getUserIdentifier() !== $this->getUser()->getUserIdentifier()){
            return $this->redirectToRoute('account');
        } 
        $list = $santaListService->getSantaListWithSantas($list);
        // dd($user);
        if(isset($_POST['updateList'])){
            // dd($_POST, $list);
            $action = $handleRegisterForm->updateList($_POST, $list);

            if($action === true){
                $this->addFlash('success', 'La liste a bien été modifiée');
                return $this->redirectToRoute('account_list_details', ['id' => $id]);
            } else {
                $this->addFlash('error', $action);
            }
        }
        return $this->render('account/updateList.html.twig', [
            'list' => $list,
        ]);
    }

    #[Route('/compte/create-liste', name: 'create_list')]
    public function createList(HandleRegisterForm $handleRegisterForm)
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('home');
        }
        if(isset($_POST['submitCreateList'])){
            $action = $handleRegisterForm->createList($_POST, $this->getUser());
            if($action === true){
                $this->addFlash('success', 'La liste a bien été créée');
                return $this->redirectToRoute('account');
            } else {
                $this->addFlash('error', $action);
            }
        }

        return $this->render('account/createList.html.twig', [

        ]);
    }

    #[Route('/compte/delete-list/{id}', name: 'delete_list')]
    public function deleteList($id, SantaListService $santaListService, SantaListRepository $santaListRepository)
    {
        if(!$this->getUser()){
            return $this->redirectToRoute('home');
        }
        $list = $santaListRepository->findOneBy(['id' => $id]);
        if(!$list || $list->getUserRelation()->getUserIdentifier() !== $this->getUser()->getUserIdentifier()){
            return $this->redirectToRoute('account');
        } 
        $santaListService->deleteList($list);
        $this->addFlash('success', 'La liste a bien été supprimée');
        return $this->redirectToRoute('account');
    }

    
}
