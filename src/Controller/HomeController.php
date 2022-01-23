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
    public function index(Request $request, HandleRegisterForm $form): Response
    {
        if(isset($_POST['createMember'])){
            // dd($request->request->all());
            $submission = $form->dispatchData($request->request->all());

            if ($submission === true) {
                $this->addFlash('success', 'Votre inscription a bien été prise en compte');
                return $this->redirectToRoute('app_login');
            } else {
                $this->addFlash('error', $submission);
            }
        }
        return $this->render('home/index.html.twig');
    }
}
