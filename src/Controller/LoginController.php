<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function index(
        AuthenticationUtils $utils
    ): Response {
        // Ottiene l'ultimo nome utente inserito dall'utente durante il tentativo di login.
        $lastUsername = $utils->getLastUsername();

        // Ottiene eventuali errori di autenticazione dall'ultimo tentativo di login.
        $error = $utils->getLastAuthenticationError();

        // Renderizza la pagina di login e passa il nome utente e l'eventuale errore alla vista.
        return $this->render('login/index.html.twig', [
            'lastUsername' => $lastUsername,
            'error' => $error
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout()
    {
    }


    // questa di base e come si gestisce il login e il log out, questo file lavora in concomitanza con security.yaml ;qui sotto lo snipet da inserire.
    // form_login:
    //     login_path: app_login
    //     check_path: app_login
    // logout:
    //     path: app_logout
    //     target: app_login

}
