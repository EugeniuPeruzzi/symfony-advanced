<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Form\CommentType;
use App\Form\MicroPostType;
use App\Repository\CommentRepository;
use App\Repository\MicroPostRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
            'posts' => $posts->findAllWithComments()
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
    // Questa riga sotto ha la stessa funzionalita delle riga 79 solo che e piu restrittiva
    // #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        //questo snipet di codice protegge la rotta ulr, senza questo se nel url viene inserito l'indirizzo anche un utente non autenticato potra creare post.
       $this->denyAccessUnlessGranted(
        'IS_AUTHENTICATED_FULLY'
        //'PUBLIC_ACCESS'
    );

        // // Creazione di un nuovo oggetto MicroPost
        // $microPost = new MicroPost();
    
        // Creazione di un form per gestire l'input dell'utente
        $form = $this->createForm(MicroPostType::class, new MicroPost);
        // Gestione della richiesta HTTP per il form
        $form->handleRequest($request);
    
        // Verifica se il form è stato inviato e se i dati sono validi
        if ($form->isSubmitted() && $form->isValid()) {
            // Ottieni i dati dal form
            $microPost = $form->getData();

            //atribuiamo al post l'utente che lo sta pubblicando.
            $microPost->setAuthor($this->getUser());
            // Persisti l'oggetto MicroPost nel database
            $entityManager->persist($microPost);
            
            // Esegui la sincronizzazione delle modifiche nel database
            $entityManager->flush();
            
            //Aggiungiamo un flash e un messagio di verifica che puo essere renderizzato nella view
            $this->addFlash('success', 'generato con successo');

            // Redirect a una pagina successiva alla creazione del MicroPost (aggiungi un URL appropriato)
            return $this->redirectToRoute('app_micro_post');
        }
    
        // Renderizza la pagina del form
        return $this->render('micro_post/new.html.twig', [
            'form' => $form->createView(), // Passa la vista del form al template Twig
        ]);
    }
    
    #[Route('/micro-post/edit/{id}', name: 'app_micro_post_edit')]
    #[IsGranted('ROLE_EDITOR')]
    public function edit(MicroPost $microPost, Request $request, EntityManagerInterface $entityManager): Response
    {

        // Creazione di un form per gestire l'input dell'utente
        $form = $this->createForm(MicroPostType::class, $microPost);
    
        // Gestione della richiesta HTTP per il form
        $form->handleRequest($request);
    
        // Verifica se il form è stato inviato e se i dati sono validi
        if ($form->isSubmitted() && $form->isValid()) {
            // Ottieni i dati dal form
            $microPost = $form->getData();
    
            // Persisti l'oggetto MicroPost nel database
            $entityManager->persist($microPost);
            
            // Esegui la sincronizzazione delle modifiche nel database
            $entityManager->flush();
            
            //Aggiungiamo un flash e un messagio di verifica che puo essere renderizzato nella view
            $this->addFlash('success', 'modificato con successo');

            // Redirect a una pagina successiva alla creazione del MicroPost (aggiungi un URL appropriato)
            return $this->redirectToRoute('app_micro_post');
        }
    
        // Renderizza la pagina del form
        return $this->render('micro_post/edit.html.twig', [
            'form' => $form->createView(), // Passa la vista del form al template Twig
        ]);
    }

    #[Route('/micro-post/comment/{id}', name: 'app_micro_post_comment')]
    #[IsGranted('ROLE_COMMENTER')]
    public function addComment(MicroPost $microPost, Request $request, EntityManagerInterface $entityManager, CommentRepository $comments): Response
    {
        // Creazione di un form per la gestione dei commenti
        $form = $this->createForm(CommentType::class, new Comment());
    
        // Gestione della richiesta HTTP per il form
        $form->handleRequest($request);
    
        // Verifica se il form è stato inviato e se i dati sono validi
        if ($form->isSubmitted() && $form->isValid()) {
            // Ottieni i dati dal form
            $comment = $form->getData();
    
            // Associa il commento al MicroPost corrente
            $comment->setPost($microPost);

            //atribuiamo al commento l'utente che lo sta pubblicando.
            $comment->setAuthor($this->getUser());
    
            // Persisti il commento nel database
            $entityManager->persist($comment);
    
            // Esegui la sincronizzazione delle modifiche nel database
            $entityManager->flush();
    
            // Aggiungi un messaggio di successo alla sessione Flash
            $this->addFlash('success', 'Commento creato con successo');
    
            // Redirect alla pagina principale dei MicroPost (aggiungi l'URL appropriato)
            return $this->redirectToRoute('app_micro_post');
        }
    
        // Renderizza la pagina dei commenti con il form e il MicroPost corrente
        return $this->render('micro_post/comment.html.twig', 
        [
            'form' => $form->createView(),
            'post' => $microPost
        ]);
    }
    

}
