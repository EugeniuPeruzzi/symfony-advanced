<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserProfile;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserProfileRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// AbstractController è una classe di base fornita da Symfony che a sua volta estende la classe Controller e aggiunge molte funzionalità e servizi utili, tra cui l'iniezione delle dipendenze, l'accesso al servizio del gestore delle richieste (Request), e l'accesso ai metodi di rendering delle viste.
class MainController extends AbstractController{
    
    private array $messages = [
        [
            'message' => 'hello' , 
            'created' => '2024/06/12'
        ], 
        [
            'message' => 'hi' , 
            'created' => '2024/04/12'
        ],
        [
            'message' => 'Bey!~' , 
            'created' => '2021/05/12'
        ],
    ];

    // Definizione di una rotta con un parametro opzionale "limit" che deve essere un intero (default: 3)
    #[Route('/', name: 'app_index')]
        public function index(UserProfileRepository $profiles, EntityManagerInterface $entityManager): Response {

            $user = new User();
            $user->setEmail('email@email.com');
            $user->setPassword('12345678');

            $profile = new UserProfile();
            $profile->setUser($user);
            $entityManager->persist($profile);
            $entityManager->flush();

            // $profile = $profiles->find(1);
            // $profiles->remove($profile, true);
            // $entityManager->flush();
        return $this->render(
            'main/index.html.twig',
            [
                'variabili' => $this->messages,
                'limit' => 3
            ]
        );
    
        // Se si desidera restituire direttamente una risposta senza il rendering del template, è possibile utilizzare la seguente riga:
        // return new Response(implode(',', array_slice($this->messages, 0, $limit)));
    }
    
    // Definizione di una rotta che accetta un parametro "id" che deve essere un intero
    #[Route('/messages/{id<\d+>}', name: "app_show_one")]
    public function showOne(int $id): Response {
        // Restituisce una risposta renderizzata utilizzando il template 'main/showOne.html.twig'
        // La variabile 'message' contiene l'elemento dell'array $this->messages corrispondente all'ID specificato
        return $this->render(
           'main/showOne.html.twig',
           ['xxx' => ($this->messages[$id])]
        );
    
        // Se si desidera restituire direttamente una risposta senza il rendering del template, è possibile utilizzare la seguente riga:
        // return new Response($this->messages[$id]);
    }
    
}
