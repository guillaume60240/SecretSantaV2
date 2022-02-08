<?php

namespace App\Services;

use App\Entity\Santa;
use App\Entity\SantaList;
use App\Repository\SantaRepository;
use Doctrine\ORM\EntityManagerInterface;

class SantasService {

    public function __construct(EntityManagerInterface $entityManager, SantaRepository $santaRepository) {
        $this->entityManager = $entityManager;
        $this->santaRepository = $santaRepository;
    }

    // crée un nouveau santa 
    public function createSanta(array $santaForm, SantaList $santaList) : void
    {
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

    // génère un token pour chaque santa pour le lien de récupération de la relation
    public function generateToken(string $santa, SantaList $santaList) : string
    {
        $token = hash('sha256', $santa . $santaList->getName());
        return $token;
    }
   
    // supprime un santa après avoir supprimer la relation s'il a un santa ou s'il est le santa d'un autre membre
    public function removeSanta(Santa $santa) : bool
    {
        $this->deleteReceiver($santa);
        $santa->setGiveGift(null);
        $this->entityManager->remove($santa);
        $this->entityManager->flush();

        return true;
    }

    // permet de modifier le nom du Santa
    public function updateSantaName(Santa $updatedSanta, string $santaName) : bool
    {
        $updatedSanta->setFirstName($santaName);
        $this->entityManager->persist($updatedSanta);
        $this->entityManager->flush();

        return true;
    }

    // permet de modifier le mail du Santa
    public function updateSantaMail(Santa $updatedSanta, string $santaMail) : bool
    {
        $updatedSanta->setEmail($santaMail);
        $this->entityManager->persist($updatedSanta);
        $this->entityManager->flush();

        return true;
    }

    // permet d'ajouter des contraintes au Santa
    public function addConstraint(Santa $santa, Santa $constraint) : bool
    {
        $santa->addCantGiveGift($constraint);
        $this->entityManager->persist($santa);
        $this->entityManager->flush();

        return true;
    }

    //permet de retirer toutes les contraintes du Santa
    public function removeConstraints(Santa $santa) : bool
    {
        $constraints = $santa->getCantGiveGift();
        foreach($constraints as $constraint) {
            $santa->removeCantGiveGift($constraint);
        }
        
        $this->entityManager->flush();

        return true;
    }

    // permet de supprimer le Santa d'un autre membre
    public function deleteReceiver(Santa $santa) : bool
    {
        $list = $santa->getSantaListRelation();
        foreach($list->getSantas() as $giver) {
            if($giver->getGiveGift() == $santa) {
            $giver->setGiveGift(null);
            }

            $this->entityManager->persist($giver);
            $this->entityManager->flush();
        }

        return true;
    }

    public function getSantaWithCantGiveGift(Santa $santa) : Santa
    {
        $constraints = $santa->getCantGiveGift();
        // dd($santa, $constraints);

        return $santa;
    }

    // permet de créer la relation santa receiver
    public function updateSantastAfterGeneration(array $datas) : bool
    {
        foreach($datas as $data) {
            $santa = $this->santaRepository->findOneBy(['id' => $data['giver']->getId()]);
            if($santa) {

                $receiver = $this->santaRepository->findOneBy(['id' => $data['newGiveGift']->getId()]);

                if($receiver) {
                    $santa->setGiveGift($receiver);
                    $this->entityManager->persist($santa);
                } else {
                    $santa->setGiveGift(null);
                    $this->entityManager->persist($santa);
                }
            }
        }
        $this->entityManager->flush();
        return true;
    }
}