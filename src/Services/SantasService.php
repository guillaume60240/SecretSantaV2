<?php

namespace App\Services;

use App\Entity\Santa;
use Doctrine\ORM\EntityManagerInterface;

class SantasService {

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createSanta($santaForm, $santaList)
    {
        // dd($santaForm, $santaList);
        foreach($santaForm as $santa) {
            $newSanta = new Santa();
            $token = $this->generateToken($santa, $santaList);
            
            $newSanta->setFirstName($santa);
            $newSanta->setToken($token);
            $newSanta->setUserRelation($santaList->getUserRelation());
            $newSanta->setSantaListRelation($santaList);

            $this->entityManager->persist($newSanta);

            $this->entityManager->flush();

        }
    }

    public function generateToken($santa, $santaList)
    {
        $token = hash('sha256', $santa . $santaList->getName());
        // dd($token);
        return $token;
    }
   
}