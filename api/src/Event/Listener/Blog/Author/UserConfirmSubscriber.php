<?php

namespace App\Event\Listener\Blog\Author;

use App\Model\Blog\Entity\Author\Author;
use App\Model\Blog\Entity\Author\AuthorRepository;
use App\Model\Blog\Entity\Author\Email;
use App\Model\Blog\Entity\Author\Id;
use App\Model\Blog\Entity\Author\Name;
use App\Model\Flusher;
use App\Model\User\Entity\User\Event\UserConfirmed;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserConfirmSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private AuthorRepository $authors,
        private Flusher $flusher,
    ) {
    }

    #[ArrayShape([UserConfirmed::class => 'string'])]
    public static function getSubscribedEvents(): array
    {
        return [
            UserConfirmed::class => 'onUserConfirmed',
        ];
    }

    public function onUserConfirmed(UserConfirmed $event): void
    {
        $id = new Id($event->id);

        if ($this->authors->has($id)) {
            throw new \DomainException('Author already exists.');
        }

        $author = new Author(
            $id,
            new Name(
                $event->firstName,
                $event->lastName
            ),
            new Email($event->email)
        );

        $this->authors->add($author);

        $this->flusher->flush();
    }
}
