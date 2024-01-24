<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Crea un nuovo account USER',
)]
class CreateUserCommand extends Command
{
    public function __construct(
        private UserPasswordHasherInterface $userPasswordHasher,
        private UserRepository $users,
        private EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        // Configura il comando definendo gli argomenti richiesti.
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'User Email')
            ->addArgument('password', InputArgument::REQUIRED, 'User Password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Crea un oggetto SymfonyStyle per gestire l'input/output della console.
        $io = new SymfonyStyle($input, $output);

        // Ottiene l'email e la password fornite come argomenti della console.
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        // Crea una nuova istanza dell'entità User e imposta l'email.
        $user = new User();
        $user->setEmail($email);

        // Utilizza il servizio UserPasswordHasherInterface per hashare la password e impostarla per l'utente.
        $user->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user,
                $password
            )
        );

        // Persiste l'utente nel database.
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // Mostra un messaggio di successo sulla console.
        $io->success(sprintf('L\'account %s è stato creato', $email));

        // Restituisce un codice di successo al sistema della console.
        return Command::SUCCESS;
    }
}
