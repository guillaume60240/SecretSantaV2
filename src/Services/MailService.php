<?php

namespace App\Services;

use App\Utils\Mailjet;

class MailService
{
    public function sendListGenerationConfirm($user, $list)
    {
        $mail = new Mailjet; 

        $mail->sendListGenerationConfirm($user->getEmail(), $user->getFirstName().' '.$user->getLastName(), 'Liste '.$list->getName().' générée.', 3540999, $list);

    }

    public function sendSecretNameToSanta($user, $santas, $list)
    {
        foreach($santas as $santa) {
            if($santa->getEmail() != null) {
                $santa->getGiveGift();
                $mail = new Mailjet; 
                // dd($santa);
                $mail->sendSecretNameToSanta( $user->getFirstName().' '.$user->getLastName(), $santa->getEmail(), $santa->getFirstName(), $list->getName(), date_format($list->getEventDate(), 'd/m/Y'), $santa->getGiveGift()->getFirstName(), 3541032);
            }
        }
    }
}