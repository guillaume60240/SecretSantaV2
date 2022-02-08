<?php

namespace App\Services;

use App\Entity\Santa;
use App\Entity\SantaList;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class SantaListService {

    public function __construct(EntityManagerInterface $entityManager, SantasService $santasService)
    {
        $this->entityManager = $entityManager;
        $this->santasService = $santasService;
    }

    public function createSantaList(array $santaListForm, User $user) : SantaList
    {
        // dd($santaListForm);
        $santaList = new SantaList();
        $santaList->setName($santaListForm['eventName']);
        $santaList->setEventDate($santaListForm['eventDate']);
        $santaList->setDescription($santaListForm['eventDescription']);
        $santaList->setGenerated(false);
        $santaList->setUserRelation($user);
        $santaList->setSendNotificationForSantaVisit(false);
        $santaList->setSendNotificationForGeneratedList(false);
        $santaList->setSendMailToSantas(true);
        $this->entityManager->persist($santaList);
        // dd($santaList);
        $this->entityManager->flush();

        return $santaList;
        
    }

    public function getSantaListWithSantas(SantaList $santaList) : SantaList
    {
        $santas = $this->entityManager->getRepository(Santa::class)->findBy(['santaListRelation' => $santaList]);
        foreach ($santas as $santa) {
            $santaList->addSanta($santa);
        }

        return $santaList;
    }

    public function updateList(array $form, SantaList $list) : bool
    {
        // dd($form);
        $list->setName($form['eventName']);
        $list->setEventDate($form['eventDate']);
        $list->setDescription($form['eventDescription']);
        $list->setSendNotificationForSantaVisit($form['sendNotificationForSantaVisit']);
        $list->setSendNotificationForGeneratedList($form['sendNotificationForGeneratedList']);
        $list->setSendMailToSantas($form['sendMailToSanta']);
        // dd($list);
        $this->entityManager->persist($list);
        $this->entityManager->flush();

        return true;
    }

    public function deleteList(SantaList $list) : bool
    {
        foreach($list->getSantas() as $santa) {
            if($santa->getCantGiveGift()) {
                $this->santasService->removeConstraints($santa);
            }
            $santa->setGiveGift(null);
            $this->entityManager->flush();
        }
        foreach($list->getSantas() as $santa) {
            $this->entityManager->remove($santa);
        }
        $this->entityManager->remove($list);
        $this->entityManager->flush();

        return true;
    }

    public function updateSantaListAfterGeneration(SantaList $list) : bool
    {
        // dd($list);
        $list->setGenerated(true);
        $this->entityManager->persist($list);
        $this->entityManager->flush();

        return true;
    }
}