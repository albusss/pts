<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Notification;
use App\Service\Notification\NotificationServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("is_granted('ROLE_USER')")
 *
 * @Route("/notification")
 */
final class NotificationController extends AbstractController
{
    /**
     * @var \App\Service\Notification\NotificationServiceInterface
     */
    private $notificationService;

    /**
     * NotificationController constructor.
     *
     * @param \App\Service\Notification\NotificationServiceInterface $notificationService
     */
    public function __construct(NotificationServiceInterface $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Acknowledge notification.
     *
     * @param \App\Entity\Notification $notification
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/acknowledge/{id}", name="notification_acknowledge")
     */
    public function acknowledge(Notification $notification): Response
    {
        $this->notificationService->acknowledge($notification);

        return $this->redirectToRoute('notification_all');
    }

    /**
     * Acknowledge all notifications.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/acknowledge-all", name="notification_acknowledge_all")
     */
    public function acknowledgeAll(): Response
    {
        $this->notificationService->acknowledgeAll($this->getUser());

        return $this->redirectToRoute('notification_all');
    }

    /**
     * Returns all of unread User notifications.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/all", name="notification_all")
     */
    public function notifications(): Response
    {
        return
            $this->render(
                'notification/notifications.html.twig',
                [
                    'notifications' => $this->notificationService->notifications($this->getUser()),
                ]
            );
    }

    /**
     * Returns count of unread notifications.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     *
     * @Route("/unread-count", name="notification_unread")
     */
    public function unreadCount(): Response
    {
        return
            new JsonResponse(
                [
                    'count' => $this->notificationService->unreadCount($this->getUser()),
                ]
            );
    }
}
