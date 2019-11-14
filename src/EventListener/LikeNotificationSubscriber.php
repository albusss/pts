<?php
declare(strict_types=1);

namespace App\EventListener;

use App\Entity\LikeNotification;
use App\Entity\MicroPost;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;

final class LikeNotificationSubscriber implements EventSubscriber
{
    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return string[]
     */
    public function getSubscribedEvents()
    {
        return [
            Events::onFlush
        ];
    }

    /**
     * @param \Doctrine\ORM\Event\OnFlushEventArgs $args
     *
     * @return void
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function onFlush(OnFlushEventArgs $args): void
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        /** @var \Doctrine\ORM\PersistentCollection $collectionUpdate */
        foreach ($uow->getScheduledCollectionUpdates() as $collectionUpdate) {
            if ($collectionUpdate->getOwner() instanceof MicroPost === false) {
                continue;
            }

            if ('likedBy' !== $collectionUpdate->getMapping()['fieldName']) {
                continue;
            }

            $insertDiff = $collectionUpdate->getInsertDiff();

            if (!\count($insertDiff)) {
                continue;
            }

            /** @var \App\Entity\MicroPost $microPost */
            $microPost = $collectionUpdate->getOwner();

            $notification = new LikeNotification();
            $notification
                ->setMicroPost($microPost)
                ->setUser($microPost->getUser())
                ->setLikedBy(\reset($insertDiff));

            $em->persist($notification);
            $uow->computeChangeSet($em->getClassMetadata(LikeNotification::class), $notification);
        }
    }
}
