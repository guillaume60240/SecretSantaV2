<?php

namespace App\Services;

use App\Entity\Santa;
use App\Repository\SantaRepository;
use Doctrine\ORM\EntityManagerInterface;

class SantasService {

    public function __construct(EntityManagerInterface $entityManager, SantaRepository $santaRepository) {
        $this->entityManager = $entityManager;
        $this->santaRepository = $santaRepository;
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
   
    public function removeSanta($santa)
    {
        $this->entityManager->remove($santa);
        $this->entityManager->flush();

        return true;
    }

    public function updateSantaName($updatedSanta, $santaName)
    {
        $updatedSanta->setFirstName($santaName);
        $this->entityManager->persist($updatedSanta);
        $this->entityManager->flush();

        return true;
    }

    public function updateSantaMail($updatedSanta, $santaMail)
    {
        $updatedSanta->setEmail($santaMail);
        $this->entityManager->persist($updatedSanta);
        $this->entityManager->flush();

        return true;
    }

    public function addConstraint($santa, $constraint)
    {
        $this->removeConstraints($santa);
        $santa->addCantGiveGift($constraint);
        $this->entityManager->persist($santa);
        $this->entityManager->flush();

        return true;
    }

    public function removeConstraints($santa)
    {
        $constraints = $santa->getCantGiveGift();
        foreach($constraints as $constraint) {
            $santa->removeCantGiveGift($constraint);
        }
        
        $this->entityManager->flush();

        return true;
    }
}