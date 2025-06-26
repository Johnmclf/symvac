<?php

namespace App\Controller;

use App\Repository\VacataireRepository;
use App\Entity\Vacataire;
use App\Form\VacataireTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class VacataireController extends AbstractController
{
    #[Route('/vacataire', name: 'app_vacataire')]
    public function index(VacataireRepository $repository,
        PaginatorInterface $paginator,
        Request $request): Response 
    {
        $vacataires = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        // dd($vacataires);

        return $this->render('pages/vacataire/index.html.twig', [
            'vacataires' => $vacataires,
        ]);
    }

    #[Route('/vacataire/nouveau','vacataire_new',methods:['GET','POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $manager
    ): Response
    {
        $vacataire = new Vacataire();
        $form = $this->createForm(VacataireTypeForm::class, $vacataire);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $vacataire = $form->getData();

            $manager->persist($vacataire);
            $manager->flush();
            $this->addFlash(
            'success',
            'Vos changements ont été enregistrés !'
            );

            return $this->redirectToRoute('app_vacataire');
        }

        return $this->render('pages/vacataire/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/vacataire/modifier/{id}', name: 'vacataire_edit', methods: ['GET', 'POST'])]
    public function edit(
        int $id,
        Request $request,
        EntityManagerInterface $manager,
        VacataireRepository $vacataireRepository
    ): Response {
        $vacataire = $vacataireRepository->find($id);

        if (!$vacataire) {
            throw $this->createNotFoundException('Vacataire non trouvé');
        }

        $form = $this->createForm(VacataireTypeForm::class, $vacataire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->flush();
            $this->addFlash('success', 'Le vacataire a bien été modifié !');

            return $this->redirectToRoute('app_vacataire');
        }

        return $this->render('pages/vacataire/update.html.twig', [
            'form' => $form,
            'vacataire' => $vacataire
        ]);
    }

    #[Route('/vacataire/supprimer/{id}', name: 'vacataire_delete', methods: ['GET', 'POST'])]
    public function delete(
        int $id,
        Request $request,
        EntityManagerInterface $manager,
        VacataireRepository $vacataireRepository
    ): Response {
        $vacataire = $vacataireRepository->find($id);

        if (!$vacataire) {
            throw $this->createNotFoundException('Vacataire non trouvé');
        }

        if ($request->isMethod('POST')) {
            $manager->remove($vacataire);
            $manager->flush();

            $this->addFlash('success', 'Le vacataire a bien été supprimé !');
            return $this->redirectToRoute('app_vacataire');
        }

        return $this->render('pages/vacataire/delete.html.twig', [
            'vacataire' => $vacataire
        ]);
    }


}
