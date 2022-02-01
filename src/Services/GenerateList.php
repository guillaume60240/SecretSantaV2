<?php

namespace App\Services;

use App\Repository\SantaListRepository;
use App\Repository\SantaRepository;

class GenerateList
{
    public function __construct(SantaListRepository $santaListRepository, SantaListService $santaListService, SantaRepository $santaRepository, SantasService $santasService)
    {
        $this->santaListRepository = $santaListRepository;
        $this->santaListService = $santaListService;
        $this->santaRepository = $santaRepository;
        $this->santasService = $santasService;
        $this->boucle = 1;
    }
    
    public function initialiseGeneration($activeList)
    {
       
        $santas = $this->santaRepository->findBy(['santaListRelation' => $activeList]);
        foreach($santas as $santa) {
            $constraints = $santa->getCantGiveGift();
            $CantReceiveGift = $santa->getCantReceiveFrom();
            $allConstraints[] = null;
            if($constraints){
                
                foreach ($constraints as $constraint) {
                    $allConstraints[] = $constraint->getId();
                }
            } else {
                $allConstraints = null;
            }
            if($CantReceiveGift){
                foreach ($CantReceiveGift as $constraint) {
                    $allConstraints[] = $constraint->getId();
                }
            } else {
                $allConstraints = null;
            }
        }
        $list = $this->santaListService->getSantaListWithSantas($activeList);

        //on génère le tableau nécessaire à la génération
        $this->datasForGeneration = $this->getDatasForGeneration($list);
        //Si le résultat n'est pas un tableau c'est que le calcul est impossible alors on stoppe la génération
        if(!is_array($this->datasForGeneration) ) {
            return $this->datasForGeneration;
        }

        $listGeneration = $this->listGeneration($this->datasForGeneration);

        if ($listGeneration === true) {
            //on update les santas
            $updataSanta = $this->santasService->updateSantastAfterGeneration($this->datasForGeneration);
            //on update la liste
            if($updataSanta) {

                $updateList = $this->santaListService->updateSantaListAfterGeneration($activeList);

                if($updateList) {
                    return true;
                }
            }
            
            // dd($list, $this->datasForGeneration);
        } else {
            return $listGeneration;
        }
    }

    public function getDatasForGeneration($list)
    {
        $datas = [];
        foreach($list->getSantas() as $santa) {
            $giver = $santa;
            $constraints = $santa->getCantGiveGift();
            $cantReceiveFrom = $santa->getCantReceiveFrom();
            $lastGiveGift = $santa->getGiveGift();
            $givePriority = $constraints->count();
            $receivePriority = $cantReceiveFrom->count();
            
            if($constraints->count() >= ($list->getSantas()->count() -1) || $cantReceiveFrom->count() >= ($list->getSantas()->count() -1)) {
                return 'Le calcul est impossible pour ' . $santa->getFirstName() . '.';
            }

            $datas[] = [
                'giver' => $giver,
                'constraints' => $constraints,
                'cantReceiveFrom' => $cantReceiveFrom,
                'santaPriority' => $givePriority,
                'receivePriority' => $receivePriority,
                'lastGiveGift' => $lastGiveGift,
                'newGiveGift' => null,
                'santa' => false,
                'receiveFrom' => false,
            ];
        }
        return $datas;
        // dd($list->getSantas(), $datas);
    }

    public function listGeneration($list)
    {
        $limit = count($list);

        array_multisort(array_column($list, 'santaPriority'), SORT_DESC, $list);
        for ($i = 0; $i < $limit; $i++) {
            $newSanta = $this->findSanta($this->datasForGeneration);
            if(!$newSanta) {
                return 'Il y a eu un problème dans la génération de la liste newSanta.';
            }

            //On va choisir une receveur
            // dd($newSanta);
            $receiver = $this->findReceiver($this->datasForGeneration, $newSanta);
            if(!$receiver) {
                return 'Il y a eu un problème dans la génération de la liste receiver.';
            }

            //on assigne le santa au receiver et inversement
            $this->datasForGeneration = $this->assignSanta($this->datasForGeneration, $newSanta, $receiver);
        }
        //on return true pour iondiquer que la génération est terminée
        return true;
        // return $this->datasForGeneration;
        // dd($this->datasForGeneration);
    }
    
    public function findSanta($list)
    {
        //on créela list de santas
        $allSantas = $list;

        //on retire ceux qui ont déjà donné un cadeau
        foreach($allSantas as $key => $santa) {
            if($santa['santa'] == true) {
                unset($allSantas[$key]);
            }
        }
        //on classe par ordre de priorité de santa
        array_multisort(array_column($allSantas, 'santaPriority'), SORT_DESC, $allSantas);
        // if($this->boucle == 2){

        //     dd($allSantas);
        // }
        $possibleSantas = [];
        foreach($allSantas as $santa) {
            
                if($santa['santaPriority'] == $allSantas[0]['santaPriority']) {
                    $possibleSantas[] = $santa;
                }
            
        }
        
        //si il y a plus de un santa possible on choisit un au hasard
        if($possibleSantas > 1) {
            // $newSanta = array_rand($possibleSantas);
            $max = count($possibleSantas) - 1;
            $key = rand(0, $max);
            $newSanta = $possibleSantas[$key];
            return $newSanta;
        } else {
            $newSanta = $allSantas[0];
        
            return $newSanta;
        }
        // dd($possibleSantas, $newSanta);
    } 

    public function findReceiver($list, $activeSanta)
    {
        //on commence par supprimer tous ceux qui ne peuvent pas recevoir de cadeau de la part du santa
        $allReceivers = $list;
        // dd($allReceivers, $activeSanta);
        foreach($allReceivers as $key => $receiver) {
            //on retire le santa actif et ceux qui ont déjà reçu un cadeau
            // dd($receiver, $activeSanta);
            if($receiver['giver'] == $activeSanta['giver'] || $receiver['receiveFrom'] != false || $receiver['giver'] == $activeSanta['lastGiveGift']) {
                unset($allReceivers[$key]);
            }
            foreach($receiver['cantReceiveFrom'] as $constraint) {
                if($constraint == $activeSanta['giver']) {
                    unset($allReceivers[$key]);
                }
            }
        }
        //on classe par ordre de priorité de receiver
        array_multisort(array_column($allReceivers, 'receivePriority'), SORT_DESC, $allReceivers);
        // dd($allReceivers);

        $possibleReceivers = [];

        foreach($allReceivers as $receiver) {
            if($receiver['receivePriority'] == $allReceivers[0]['receivePriority']) {
                $possibleReceivers[] = $receiver;
            }
        }

        //s'il ny a pas de receiver possible on réinitialise les données et on ajoute +1 au receiverPriority et au santaPriority pour ceux qui n'ont pas donné de cadeau
        if(!$possibleReceivers) {
            $this->datasForGeneration = $this->resetDatasAndIncrementeReceiverPriority($this->datasForGeneration);
            //on increment la boucle
            $this->boucle ++;
            if($this->boucle == 20) {
                return false;
                // dd($this->datasForGeneration);
            }
            //et on relance la génération
            $this->listGeneration($this->datasForGeneration);
        }
        
        //si il y a plus de un receiver possible on choisit un au hasard
        if($possibleReceivers > 1) {
            // $receiver = array_rand($possibleReceivers);
            $max = count($possibleReceivers) - 1;
            $key = rand(0, $max);
            $receiver = $possibleReceivers[$key];
            return $receiver;
        } else {
            $receiver = $allReceivers[0];
            return $receiver;
        }

    }

    public function assignSanta($datas, $newSanta, $receiver)
    {
        foreach($datas as $key => $data) {
            if($data['giver'] == $newSanta['giver']) {
                $datas[$key]['santa'] = true;
                $datas[$key]['newGiveGift'] = $receiver['giver'];
            }
            if($data['giver'] == $receiver['giver']) {
                $datas[$key]['receiveFrom'] = $newSanta['giver'];
            }
        }

        return $datas;
    }

    public function resetDatasAndIncrementeReceiverPriority($datas)
    {
        // if($this->boucle == 19) {
        //     dd($this->datasForGeneration);
        // }
        foreach($datas as $key => $data) {
            if($data['receiveFrom'] == false) {
                $datas[$key]['receivePriority'] ++;
            }
            if($data['santa'] == false) {
                $datas[$key]['santaPriority'] ++;
            }
            $datas[$key]['receiveFrom'] = false;
            $datas[$key]['santa'] = false;
            $datas[$key]['newGiveGift'] = null;
        }
        // dd($datas);
        return $datas;
    }
    
}