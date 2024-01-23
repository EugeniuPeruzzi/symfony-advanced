<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Repository\MicroPostRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class MicroPostController extends AbstractController
{
    #[Route('/micro-post', name: 'app_micro_post')]
    public function index(MicroPostRepository $posts, EntityManagerInterface $entityManager ): Response
    {

        // dd($posts -> findAll());

        // Nelle versioni precedenti si utilizzierebbe 'add' pero non va piu bene e si utilizza EntityManagerInterface di seguito il codice.
        // $posts->add($microPost,  true);



        // Aggiungere una nuova classe
        // $microPost = new MicroPost();
        // $microPost->setTitle('Sono stato apena aggiunto!!');
        // $microPost->setText('CIAOO!');
        // $microPost->setDatetime(new DateTime());

        //$entityManager->persist($microPost);
        // $entityManager->flush();

        // per MODIFICARE:
        // $microPost = $posts->find(2);
        // $microPost->setTitle('Ho cambiato titolo');
        
        //  e se modifico qualcosa nei record ho solo bisogno di flush() e non persist().
        // $entityManager->flush();

        
        // per cancellare:
        // $microPost = $posts->find(2);
        // $entityManager->remove($microPost);
        // $entityManager->flush();

        // ritorna alla vista 
        return $this->render('micro_post/index.html.twig', [
            'posts' => $posts->findAll()
        ]);
    }


    #[Route('/micro-post/{id}', name: 'app_micro_post_show')]
    public function showOne($id, MicroPostRepository $microPostRepository): Response
    {
        $microPost = $microPostRepository->find($id);
        // dd($microPost);
        // Ora hai l'oggetto $microPost e puoi passarlo alla tua vista
        return $this->render('micro_post/show.html.twig', [
            'microPost' => $microPost,
        ]);
    }

    #[Route('/micro-post/new', name: 'app_micro_post_new', priority: 2)]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Creazione di un nuovo oggetto MicroPost
        $microPost = new MicroPost();
    
        // Creazione di un form per gestire l'input dell'utente
        $form = $this->createFormBuilder($microPost)
            ->add('title')
            ->add('text')
            ->add('submit', SubmitType::class, ['label' => 'save'])
            ->getForm();
    
        // Gestione della richiesta HTTP per il form
        $form->handleRequest($request);
    
        // Verifica se il form è stato inviato e se i dati sono validi
        if ($form->isSubmitted() && $form->isValid()) {
            // Ottieni i dati dal form
            $microPost = $form->getData();
    
            // Imposta la data e l'ora corrente
            $microPost->setDatetime(new DateTime());
    
            // Persisti l'oggetto MicroPost nel database
            $entityManager->persist($microPost);
            
            // Esegui la sincronizzazione delle modifiche nel database
            $entityManager->flush();
    
            // Redirect a una pagina successiva alla creazione del MicroPost (aggiungi un URL appropriato)
            return $this->redirectToRoute('app_micro_post');
        }
    
        // Renderizza la pagina del form
        return $this->render('micro_post/new.html.twig', [
            'form' => $form->createView(), // Passa la vista del form al template Twig
        ]);
    }
    
}
