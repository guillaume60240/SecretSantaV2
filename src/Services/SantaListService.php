<?php

namespace App\Services;

use App\Entity\SantaList;
use Doctrine\ORM\EntityManagerInterface;

class SantaListService {

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createSantaList($santaListForm, $user)
    {
        // dd($santaListForm);
        $santaList = new SantaList();
        $santaList->setName($santaListForm['eventName']);
        $santaList->setEventDate($santaListForm['eventDate']);
        $santaList->setDescription($santaListForm['eventDescription']);
        $santaList->setGenerated(false);
        $santaList->setUserRelation($user);
        $this->entityManager->persist($santaList);
        // dd($santaList);
        $this->entityManager->flush();

        return $santaList;
        
    }
}