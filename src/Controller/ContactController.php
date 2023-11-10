<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{

  #[Route('/contact', name: 'app_contact')]
  public function index(Request $request, EntityManagerInterface $entityManager): Response
  {
    $contact = new Contact();
    $form = $this->createForm(ContactType::class, $contact);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
      $contact = $form->getData();
      $entityManager->persist($contact);
      $entityManager->flush();

      return $this->redirectToRoute('app_contact');
    }

    $cards = [
      ['content' => 1, 'special' => ''],
      ['content' => 2, 'special' => ''],
      ['content' => 3, 'special' => ''],
      ['content' => 4, 'special' => ''],
      ['content' => 1, 'special' => ''],
      ['content' => 2, 'special' => ''],
      ['content' => 3, 'special' => ''],
      ['content' => 4, 'special' => ''],
      ['content' => 5, 'special' =>'card-special'],
    ];
    shuffle($cards);

    return $this->render('contact/index.html.twig', [
      'form' => $form->createView(),
      'cards' => $cards,
    ]);
  }

  #[Route('/contact/list', name: 'app_contact_list')]
  public function list(EntityManagerInterface $entityManager): Response
  {
    $contacts = $entityManager->getRepository(Contact::class)->findAll();

    return $this->render('contact/list.html.twig', [
      'contacts' => $contacts,
    ]);
  }
}
