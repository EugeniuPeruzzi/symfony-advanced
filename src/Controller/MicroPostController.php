<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Repository\MicroPostRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
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
    public function add() : Response {

        $microPost = new MicroPost();
        
        $form = $this->createFormBuilder($microPost)
            ->add('title')
            ->add('text')
            ->add('submit', SubmitType::class, ['label' => 'save'])
            ->getForm();

        return $this->render( 'micro_post/new.html.twig', [
            'form' => $form
        ]);
    }
}
