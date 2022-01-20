<?php

namespace App\Services;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    

    public function createUser($createUserForm)
    {
        $existedEmail = $this->verifyEmail($createUserForm['email']);
        if ($existedEmail) {
            return $existedEmail;
        }
        $newuser = new User();
        $newuser->setFirstName($createUserForm['firstName']);
        $newuser->setLastName($createUserForm['lastName']);
        $newuser->setEmail($createUserForm['email']);
        $newuser->setPassword($createUserForm['password']);
        $this->entityManager->persist($newuser);
        // dd($newuser);
        $this->entityManager->flush();
        return $newuser;
    }

    public function verifyEmail($email)
    {
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        // dd($user);
        if ($user) {
            return true;
        } else {
            return false;
        }
    }
}