<?php

namespace App\Services;

use App\Entity\User;
use App\Repository\SantaRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HandleRegisterForm
{
    public function __construct(UserPasswordHasherInterface $hasher, UserService $userService, SantaListService $santaListService, SantasService $santasService, SantaRepository $santaRepository)
    {
        $this->hasher = $hasher;
        $this->userService = $userService;
        $this->santaListService = $santaListService;
        $this->santasService = $santasService;
        $this->santaRepository = $santaRepository;
    }
    
    public function dispatchData($registerForm)
    {
        if ($registerForm['checkValidation'] == 'false') {
            return  'Merci d\'accepter les conditions générales d\'utilisation';
        } else {
            if ($registerForm['password'] != $registerForm['repeatPassword'] || !preg_match("/(?=.*[A-Z])(?=.*[a-z])(?=.*\d)[A-Za-z\d]{8,50}/", $registerForm['password'])) {
                return 'Les mots de passe ne sont pas valides';
            } else {
                if (!preg_match("/([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})/", $registerForm['email'])){
                    return 'L\'adresse email n\'est pas valide';
                } else {
                    if (!preg_match("/[.A-Za-z0-9àáâãäåçèéêëìíîïðòóôõöùúûüýÿ]{3,100}/i", $registerForm['firstName'])){
                        return 'Votre prénom n\'est pas valide';
                    } else {
                        if (!preg_match("/[.A-Za-z0-9àáâãäåçèéêëìíîïðòóôõöùúûüýÿ]{3,100}/i", $registerForm['lastName'])) {
                            return 'Votre nom n\'est pas valide';
                        } else {
                            $user = new User();
                            $userData = [
                                'firstName' => htmlspecialchars($registerForm['firstName']),
                                'lastName' => htmlspecialchars($registerForm['lastName']),
                                'email' => htmlspecialchars($registerForm['email']),
                                'password' => $this->hasher->hashPassword($user, $registerForm['password']),
                            ];
                            // appel du service createUser
                            $createdUser = $this->userService->createUser($userData);
                            // dd($createdUser);
                        }
                    }
                }
            }
            
            if (!preg_match("/[.A-Za-z0-9àáâãäåçèéêëìíîïðòóôõöùúûüýÿ]{3,100}/i", $registerForm['eventName'])) {
                return 'Le nom de l\'événement n\'est pas valide';
            } else {
                $today = new \DateTime();
                $eventDate = new \DateTime($registerForm['eventDate']);
                if ($eventDate < $today) {
                    return 'La date de l\'événement n\'est pas valide';
                } else {
                    if (!empty($registerForm['eventDescription']) && !preg_match("/[.A-Za-z0-9àáâãäåçèéêëìíîïðòóôõöùúûüýÿ]{3,300}/i", $registerForm['eventDescription'])) {
                        return 'La description de l\'événement n\'est pas valide';
                    } else {
                        $santaListData = [
                            'eventName' => htmlspecialchars($registerForm['eventName']),
                            'eventDate' => $eventDate,
                            'eventDescription' => htmlspecialchars($registerForm['eventDescription']),
                        ];
                        // appel du service createSantaList
                        $createdSantaList = $this->santaListService->createSantaList($santaListData, $createdUser);
                    }
                }
            }
            
            if (!empty($registerForm['allMembersName'])) {

                $santasData = explode(',', $registerForm['allMembersName']);
                foreach($santasData as $santaData) {
                    if (!preg_match("/[.A-Za-z0-9àáâãäåçèéêëìíîïðòóôõöùúûüýÿ]{3,100}/i", $santaData)) {
                        return 'Le nom d\'un membre n\'est pas valide';
                    }   
                }
                if(isset($registerForm['userIsMember']) && $registerForm['userIsMember'] == 'true') {
                            
                    array_push($santasData, htmlspecialchars($registerForm['firstName']).' '.htmlspecialchars($registerForm['lastName']));
                } 
                $this->santasService->createSanta($santasData, $createdSantaList);
            } else if (isset($registerForm['userIsMember']) && $registerForm['userIsMember'] == 'true'  && empty($registerForm['allMembersName'])) {
                $santasData = htmlspecialchars($registerForm['firstName']).' '.htmlspecialchars($registerForm['lastName']);
                $this->santasService->createSanta($santasData, $createdSantaList);
            }
            // appel du service createSanta
            // dd($registerForm, $userData, $santaListData, $santasData);
            return true;
        }
    }

    public function addSanta($santaForm, $activeList)
    {
        if (!empty($santaForm['allMembersName'])) {

            $santasData = explode(',', $santaForm['allMembersName']);
            foreach($santasData as $santaData) {
                if (!preg_match("/[.A-Za-z0-9àáâãäåçèéêëìíîïðòóôõöùúûüýÿ]{3,100}/i", $santaData)) {
                    return 'Le nom d\'un membre n\'est pas valide';
                }   
            }
            
            $this->santasService->createSanta($santasData, $activeList);
        }

        return true;
    }

    public function updateSantaName($santaForm, $santaList, $user)
    {
        if (!empty($santaForm['updateSantaName'])) {
            // dd($santaForm);
            $updatedSanta = $this->santaRepository->findOneBy(['id' => $santaForm['updateSantaName']]);
            if(!$updatedSanta){
                return 'Une erreur est survenue';
            }
            if($updatedSanta->getUserRelation()->getUserIdentifier() !== $user->getUserIdentifier()){
                return 'Vous n\'avez pas le droit de modifier ce santa';
            }
            if($updatedSanta->getSantaListRelation() !== $santaList){
                return 'Vous n\'avez pas le droit de modifier ce santa';
            }
            if (!preg_match("/[.A-Za-z0-9àáâãäåçèéêëìíîïðòóôõöùúûüýÿ]{3,100}/i", $santaForm['memberName'])) {
                return 'Le nom d\'un membre n\'est pas valide';
            }  
            
            $this->santasService->updateSantaName($updatedSanta, htmlspecialchars($santaForm['memberName']));
            
            return true;
        } else {
            return 'Veuillez renseigner un nom de santa';
        }

    }

    public function updateSantaMail($form, $list, $user)
    {
        if (!empty($form['updateSantaMail'])) {
            // dd($form);
            $updatedSanta = $this->santaRepository->findOneBy(['id' => $form['updateSantaMail']]);
            if(!$updatedSanta){
                return 'Une erreur est survenue';
            }
            if($updatedSanta->getUserRelation()->getUserIdentifier() !== $user->getUserIdentifier()){
                return 'Vous n\'avez pas le droit de modifier ce santa';
            }
            if($updatedSanta->getSantaListRelation() !== $list){
                return 'Vous n\'avez pas le droit de modifier ce santa';
            }
            if (!preg_match("/([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})/", $form['memberEmail'])) {
                return 'L\'adresse mail d\'un membre n\'est pas valide';
            }  
            
            $this->santasService->updateSantaMail($updatedSanta, htmlspecialchars($form['memberEmail']));
            
            return true;
        } else {
            return 'Veuillez renseigner une adresse mail de santa';
        }

    }

    public function updateList($form, $list)
    {
        if (!preg_match("/[.A-Za-z0-9àáâãäåçèéêëìíîïðòóôõöùúûüýÿ]{3,100}/i", $form['eventName'])) {
            return 'Le nom de l\'événement n\'est pas valide';
        } else {
            $today = new \DateTime();
            $eventDate = new \DateTime($form['eventDate']);
            if ($eventDate < $today) {
                return 'La date de l\'événement n\'est pas valide';
            } else {
                if (!empty($form['eventDescription']) && !preg_match("/[.A-Za-z0-9àáâãäåçèéêëìíîïðòóôõöùúûüýÿ]{3,300}/i", $form['eventDescription'])) {
                    return 'La description de l\'événement n\'est pas valide';
                } else {
                    $santaListData = [
                        'eventName' => htmlspecialchars($form['eventName']),
                        'eventDate' => $eventDate,
                        'eventDescription' => htmlspecialchars($form['eventDescription']),
                    ];
                    // appel du service update list
                    $submission = $this->santaListService->updateList($santaListData, $list);

                    return $submission;
                }
            }
        }
    }

    public function createList($form, $user)
    {
        // dd($form);
        if (!preg_match("/[.A-Za-z0-9àáâãäåçèéêëìíîïðòóôõöùúûüýÿ]{3,100}/i", $form['eventName'])) {
            return 'Le nom de l\'événement n\'est pas valide';
        } else {
            $today = new \DateTime();
            $eventDate = new \DateTime($form['eventDate']);
            if ($eventDate < $today) {
                return 'La date de l\'événement n\'est pas valide';
            } else {
                if (!empty($form['eventDescription']) && !preg_match("/[.A-Za-z0-9àáâãäåçèéêëìíîïðòóôõöùúûüýÿ]{3,300}/i", $form['eventDescription'])) {
                    return 'La description de l\'événement n\'est pas valide';
                } else {
                    $santaListData = [
                        'eventName' => htmlspecialchars($form['eventName']),
                        'eventDate' => $eventDate,
                        'eventDescription' => htmlspecialchars($form['eventDescription']),
                    ];
                    // appel du service create list
                    $createdSantaList = $this->santaListService->createSantaList($santaListData, $user);
                }
            }
            // dd($form['allMembersName']);
            if (!empty($form['allMembersName'])) {
                
                $santasData = explode(',', $form['allMembersName']);
                foreach($santasData as $santaData) {
                    if (!preg_match("/[.A-Za-z0-9àáâãäåçèéêëìíîïðòóôõöùúûüýÿ]{3,100}/i", $santaData)) {
                        return 'Le nom d\'un membre n\'est pas valide';
                    }   
                }
                if(isset($form['userIsMember']) && $form['userIsMember'] == 'true') {
                    
                    array_push($santasData, htmlspecialchars($user->getFirstName().' '.$user->getLastName()));
                } 
                // dd($santasData);
                $this->santasService->createSanta($santasData, $createdSantaList);
            } else if (isset($form['userIsMember']) && $form['userIsMember'] == 'true'  && empty($form['allMembersName'])) {
                $santasData = htmlspecialchars($user->getFirstName().' '.$user->getLastName());
                $this->santasService->createSanta($santasData, $createdSantaList);
            }

            return true;
        }
    }
}