<?php

namespace App\Services;

use App\Entity\User;
use App\Repository\SantaListRepository;
use App\Repository\SantaRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserService
{
    public function __construct(EntityManagerInterface $entityManager, SantaListRepository $santaListRepository, SantaRepository $santaRepository)
    {
        $this->entityManager = $entityManager;
        $this->santaListRepository = $santaListRepository;
        $this->santaRepository = $santaRepository;
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

    public function getUserWithListsAndSantas($user)
    {
        $lists = $this->santaListRepository->findBy(['userRelation' => $user]);
        foreach ($lists as $list) {
            $santas = $this->santaRepository->findBy(['santaListRelation' => $list]);
            foreach ($santas as $santa) {
                $list->addSanta($santa);
            }
            $user->addSantaList($list);
        }

        return $user;
    }
}