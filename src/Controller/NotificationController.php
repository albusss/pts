<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Notification;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('ROLE_USER')")
 * @Route("/notification")
 */
final class NotificationController extends AbstractController
{
    /**
     * @Route("/acknowledge/{id}", name="notification_acknowledge")
     */
    public function acknowledge(Notification $notification)
    {
        $notification->setSeen(true);
        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('notification_all');
    }

    /**
     * @Route("/acknowledge-all", name="notification_acknowledge_all")
     */
    public function acknowledgeAll()
    {
        /** @var \App\Repository\NotificationRepository $repo */
        $repo = $this->getDoctrine()->getRepository(Notification::class);

        $repo->markAllAsReadByUser($this->getUser());

        $this->getDoctrine()->getManager()->flush();

        return $this->redirectToRoute('notification_all');
    }

    /**
     *
     * @Route("/all", name="notification_all")
     */
    public function notifications()
    {
        /** @var \App\Repository\NotificationRepository $repo */
        $repo = $this->getDoctrine()->getRepository(Notification::class);

        return $this->render('notification/notifications.html.twig', [
            'notifications' => $repo->findBy([
                'seen' => false,
                'user' => $this->getUser()
            ])
        ]);
    }

    /**
     * @Route("/unread-count", name="notification_unread")
     */
    public function unreadCount()
    {
        /** @var \App\Repository\NotificationRepository $repo */
        $repo = $this->getDoctrine()->getRepository(Notification::class);

        return new JsonResponse(['count' => $repo->findUnseenByUser($this->getUser())]);
    }
}
