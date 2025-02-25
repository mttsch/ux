<?php

namespace App\Controller;

use App\Form\SendNotificationForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Asset\Packages;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Routing\Annotation\Route;

class NotifierController extends AbstractController
{
    #[Route('/notify', name: 'app_notify')]
    public function notify(Request $request, NotifierInterface $notifier): Response
    {
        $form = $this->createForm(SendNotificationForm::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message = SendNotificationForm::getTextChoices()[$form->getData()['message']];

            // custom_mercure_chatter_transport is configured in config/packages/notifier.yaml
            // it ultimately points to "mercure://default?topic=/demo/notifier"
            $notification = new Notification($message, ['chat/custom_mercure_chatter_transport']);
            $notifier->send($notification);

            return $this->redirectToRoute('app_notify');
        }

        return $this->render('notifier/notify.html.twig', [
            'form' => $form,
        ]);
    }
}
