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

    public function updateSantaName($santa, $santaList, $user)
    {
        $updatedSanta = $this->santaRepository->findOneBy(['id' => $santa['updateSantaName']]);
        if(!$updatedSanta){
            return 'Une erreur est survenue';
        }
        if($updatedSanta->getUserRelation()->getUserIdentifier() !== $user->getUserIdentifier()){
            return 'Vous n\'avez pas le droit de modifier ce santa';
        }
        if($updatedSanta->getSantaListRelation() !== $santaList){
            return 'Vous n\'avez pas le droit de modifier ce santa';
        }

        $updatedSanta->setFirstName($santa['memberName']);
        $this->entityManager->persist($updatedSanta);
        $this->entityManager->flush();

        return true;
    }
}