<?php
 namespace App\Controller;
 use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
 use Symfony\Component\HttpFoundation\Response;
 use Symfony\Component\Routing\Annotation\Route;
 class HomeController extends AbstractController
 {
 	#[Route('/', 'home.index', methods: ['GET'])]
 	public function index(): Response
 	{
 		 return $this->render('pages/vacataire/index.html.twig', ['controller_name' => 'VacataireController',]);
 	}
 }
