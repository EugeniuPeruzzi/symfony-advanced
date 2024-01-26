<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\User;
use App\Entity\MicroPost;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{


        /**
     * Costruttore della classe.
     *
     * @param UserPasswordHasherInterface $userPasswordHasher
     *   Un'istanza di UserPasswordHasherInterface utilizzata per l'hashing delle password.
     */
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher) {
        // Inizializza la classe con un'istanza di UserPasswordHasherInterface fornita come dipendenza.
        // Questo sarà utilizzato per l'hashing delle password all'interno della classe.
    }
        
    public function load(ObjectManager $manager): void
    {

        // Creazione di una nuova istanza dell'entità User.
        $user1 = new User();

        // Impostazione dell'indirizzo email per l'utente.
        $user1->setEmail('test@test.com');

        // Hash della password e impostazione della password hashata per l'utente.
        $user1->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user1,
                '12345678' // La password in chiaro da hashare.
            )
        );
        $manager->persist($user1);

        $user2 = new User();
        $user2->setEmail('john@test.com');
        $user2->setPassword(
            $this->userPasswordHasher->hashPassword(
                $user2,
                '12345678'
            )
        );
        $manager->persist($user2);




        $microPost1 = new MicroPost();
        $microPost1->setTitle('Welcome to Poland!');
        $microPost1->setText('Welcome to Poland!');
        $microPost1->setDatetime(new DateTime);
        $microPost1->setAuthor($user1);
        $manager->persist($microPost1);

        $microPost2 = new MicroPost();
        $microPost2->setTitle('Welcome to US!');
        $microPost2->setText('Welcome to US!');
        $microPost2->setDatetime(new DateTime());
        $microPost2->setAuthor($user2);
        $manager->persist($microPost2);

        $microPost3 = new MicroPost();
        $microPost3->setTitle('Welcome to Germany!');
        $microPost3->setText('Welcome to Germany!');
        $microPost3->setDatetime(new DateTime());
        $microPost3->setAuthor($user2);
        $manager->persist($microPost3);

        $manager->flush();
    }
}
