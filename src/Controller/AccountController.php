<?php

namespace App\Controller;

use App\Repository\SantaListRepository;
use App\Repository\UserRepository;
use App\Services\SantaListService;
use App\Services\SantasService;
use App\Services\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    #[Route('/compte', name: 'account')]
    public function index(UserRepository $userRepository, UserService $userService): Response
    {   
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
    public function lists(SantaListRepository $santaListRepository, SantaListService $santaListService, SantasService $santasService, $id): Response
    {   
        $list = $santaListRepository->findOneBy(['id' => $id]);
        if(!$list || $list->getUserRelation()->getUserIdentifier() !== $this->getUser()->getUserIdentifier()){
            return $this->redirectToRoute('account');
        } 
        $list = $santaListService->getSantaListWithSantas($list);
        // dd($user);
        if(isset($_POST['updateSantaName'])){
            $action = $santasService->updateSantaName($_POST, $list, $this->getUser());
            if($action === true){
                $this->addFlash('success', 'Le nom du santa a bien été modifié');
                return $this->redirectToRoute('account_list_details', ['id' => $id]);
            } else {
                $this->addFlash('error', $action);
            }
        }
        return $this->render('account/listDetails.html.twig', [
            'list' => $list,
        ]);
    }
}
