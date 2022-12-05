<?php


namespace App\Service;

use App\Entity\Client;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;

class ClientManager
{

    private EntityManagerInterface $em;

	public function __construct(EntityManagerInterface $em, ClientRepository $clientRepository, MailerInterface $mailer)
	{
		$this->em = $em;
		$this->clientRepository = $clientRepository;
		$this->mailer = $mailer;
	}

    public function wishBirthday(): int
	{
		$clients = $this->clientRepository->findAll();
        $count = 0;
		foreach ($clients as $client) {
            $birthday = $client->getBirthDate()->format("Y-m-d H:i:s");
            if(date('m-d') == substr($birthday,5,5) or (date('y')%4 <> 0 and substr($birthday,5,5)=='02-29' and date('m-d')=='02-28')){
                $count += 1;
                $email = (new TemplatedEmail())
                    ->from("Ubereats@manager.com")
                    ->to("you@example.com")
                    ->subject("Happy Birthday !");
                $this->mailer->send($email);
            }
		}

		return $count;
	}

}
