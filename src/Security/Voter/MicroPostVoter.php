<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Entity\MicroPost;
use Symfony\Bundle\SecurityBundle\Security; // Importazione del componente SecurityBundle di Symfony
use Symfony\Component\Security\Core\User\UserInterface; // Importazione dell'interfaccia UserInterface
use Symfony\Component\Security\Core\Authorization\Voter\Voter; // Importazione della classe base Voter
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface; // Importazione dell'interfaccia TokenInterface

class MicroPostVoter extends Voter
{
    public function __construct(
        private Security $security) // Iniezione della dipendenza del servizio Security
    {
    }

    // Questo metodo verifica se il Voter supporta un dato attributo e un determinato soggetto
    protected function supports(string $attribute, $subject): bool
    {
        // In questo caso, controlla se l'attributo è "EDIT" o "VIEW" e se il soggetto è un'istanza di MicroPost
        return in_array($attribute, [MicroPost::EDIT, MicroPost::VIEW])
            && $subject instanceof MicroPost;
    }

    /**
     * @param MicroPost $subject
     */
    // Questo metodo esegue il voto sulla concessione dei permessi
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        /** @var User $user  */
        $user = $token->getUser(); // Ottiene l'utente dal token
        // Se l'utente è anonimo, non concedere l'accesso
        // if (!$user instanceof UserInterface) {
        //     return false;
        // }
        $isAuth = $user instanceof UserInterface; // Verifica se l'utente è autenticato

        // Se l'utente ha il ruolo "ROLE_ADMIN", concedi l'accesso indipendentemente dall'attributo
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        // Switch su tutti gli attributi possibili
        switch ($attribute) {
            case MicroPost::EDIT:
                // L'utente deve essere autenticato e:
                // - Deve essere l'autore del MicroPost
                // - Oppure deve avere il ruolo "ROLE_EDITOR"
                return $isAuth
                    && (
                        ($subject->getAuthor()->getId() === $user->getId()) ||
                        $this->security->isGranted('ROLE_EDITOR')
                    );
            case MicroPost::VIEW:
                // Ogni utente autenticato può visualizzare un MicroPost
                return true;
        }

        return false; // Restituisce false se nessuna delle condizioni sopra è soddisfatta
    }
}
