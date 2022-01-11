<?php

namespace App\Services;

use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HandleRegisterForm
{
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
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
                            'eventDate' => htmlspecialchars($registerForm['eventDate']),
                            'eventDescription' => htmlspecialchars($registerForm['eventDescription']),
                        ];
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
                if($registerForm['userIsMember'] === 'true') {
                            
                    array_push($santasData, htmlspecialchars($registerForm['firstName']).' '.htmlspecialchars($registerForm['lastName']));
                } 
            } else if ($registerForm['userIsMember'] === 'true' && empty($registerForm['allMembersName'])) {
                $santasData = htmlspecialchars($registerForm['firstName']).' '.htmlspecialchars($registerForm['lastName']);
            }
            return true;
            // dd($registerForm, $userData, $santaListData, $santasData);
        }
    }
}