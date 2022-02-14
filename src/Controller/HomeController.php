<?php

namespace App\Controller;

use App\Services\HandleRegisterForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request, HandleRegisterForm $registerform): Response
    {
        if($this->getUser()) {
            return $this->redirectToRoute('account');
        }

        if($request->isMethod('POST')){
            $form = $request->request->all();
            if(isset($form['createMember'])){
                $submission = $registerform->dispatchData($form);
                if ($submission === true) {
                    $this->addFlash('success', 'Votre inscription a bien été prise en compte');
                    return $this->redirectToRoute('app_login');
                } else {
                    $this->addFlash('error', $submission);
                }
            }
        }

        return $this->render('home/index.html.twig');
    }
}
