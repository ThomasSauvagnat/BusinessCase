<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class TestMailController extends AbstractController
{
    #[Route('/test/mail', name: 'app_test_mail')]
    public function index(MailerInterface $mailer): Response
    {
        $email = new Email();
        $email->from('animalerie30@gmail.com')
            ->to('ts42@hotmail.fr')
            ->subject('Mon beau sujet !')
//            ->text('qfsdfsdf') => c'est déconseillé
            ->html('<p style="color: red">Bonjour tout le monde !</p>');

        $mailer->send($email);

        return $this->render('test_mail/index.html.twig', [
            'controller_name' => 'TestMailController',
        ]);
    }
}
