<?php

namespace App\Controller;

use App\Entity\Template;
use App\Form\Type\Post\PostSearchType;
use App\Form\Type\Template\TemplateType;
use App\Repository\PostRepository;
use App\Repository\TemplateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TemplateController extends AbstractController
{
    #[Route('/templates', name: 'app_template')]
    public function index(TemplateRepository $repository): Response
    {
        $templates = $repository->findAll();

        return $this->render('template/index.html.twig', [
            'templates' => $templates,
        ]);
    }

    #[Route('/templates/new', name: 'app_template_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $template = new Template();

        $form = $this->createForm(TemplateType::class, $template);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Template $formTemplate */
            $formTemplate =  $form->getData();

            if ($form->has('fieldOptions')) {
                dump($form->get('fieldOptions'));
            }

            $entityManager->persist($formTemplate);
            $entityManager->flush();

            $this->addFlash('success', sprintf('Template %s created', $formTemplate->getName()));

            return $this->redirectToRoute('app_template');
        }

        return $this->render('template/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/templates/{id}/update', name: 'app_template_update', methods: ['GET', 'POST'])]
    public function update(Request $request, EntityManagerInterface $entityManager, Template $template): Response
    {
        $form = $this->createForm(TemplateType::class, $template);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Template $formTemplate */
            $formTemplate = $form->getData();

            $entityManager->persist($formTemplate);
            $entityManager->flush();

            $this->addFlash('success', sprintf('Template %s updated', $formTemplate->getName()));

            return $this->redirectToRoute('app_template');
        }

        return $this->render('template/update.html.twig', [
            'form' => $form,
            'template' => $template
        ]);
    }

    #[Route('/templates/{systemName}/details', name: 'app_template_details', methods: ['GET'])]
    public function details(Request $request, TemplateRepository $templateRepository, PostRepository $postRepository, string $systemName): Response
    {
        $template = $templateRepository->findBySystemName($systemName);
        if (!$template instanceof Template) {
            throw $this->createNotFoundException();
        }

        $searchForm = $this->createForm(PostSearchType::class, $template->getTemplateFields(), [
            'request' => $request,
            'method' => 'GET',
        ]);

        $queryParams = $request->query->all()['post_search'] ?? [];
        $posts = $postRepository->searchByTemplateFields($template, $queryParams);

        return $this->render('template/details.html.twig', [
            'template' => $template,
            'searchForm' => $searchForm,
            'posts' => $posts,
        ]);
    }
}
