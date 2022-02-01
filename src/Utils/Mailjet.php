<?php

namespace App\Utils;

use Mailjet\Client;
use Mailjet\Resources;

class Mailjet{
    

    public function sendListGenerationConfirm( $to_email, $to_name, $subject, $template, $list){
        $mj= new Client($_ENV['MAILJET_PUBLIC'], $_ENV['MAILJET_SECRET'],true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $_ENV['PRIVATE_MAIL'],
                        'Name' => "Secret Santa"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' => $to_name
                        ]
                    ],
                    'TemplateID' => $template,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => [
                        'name' => $to_name,
                        'listName' => $list->getName(),
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }

    public function sendSecretNameToSanta( $userName, $santaMail, $santaName, $eventName, $eventDate, $receiverName, $template){
        $mj= new Client($_ENV['MAILJET_PUBLIC'], $_ENV['MAILJET_SECRET'],true,['version' => 'v3.1']);
        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => $_ENV['PRIVATE_MAIL'],
                        'Name' => "Secret Santa"
                    ],
                    'To' => [
                        [
                            'Email' => $santaMail,
                            'Name' => $santaName
                        ]
                    ],
                    'TemplateID' => $template,
                    'TemplateLanguage' => true,
                    'Subject' => "Secret Santa",
                    'Variables' => [
                        'name' => $santaName,
                        'userName' => $userName,
                        'eventName' => $eventName,
                        'eventDate' => $eventDate,
                        'receiverName' => $receiverName,
                    ]
                ]
            ]
        ];
        $response = $mj->post(Resources::$Email, ['body' => $body]);
        $response->success();
    }
   
}