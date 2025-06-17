<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Template;
use App\Form\Type\Post\PostType;
use App\Repository\PostRepository;
use App\Repository\TemplateRepository;
use App\Service\EntityService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PostController extends AbstractController
{
    #[Route('/', name: 'app_post')]
    public function index(PostRepository $repository): Response
    {
        $posts = $repository->findAll();
        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/blog/new', name: 'app_post_new')]
    public function new(TemplateRepository $templateRepository, EntityManagerInterface $entityManager, Request $request, EntityService $entityService): Response
    {
        $post = new Post();
        $template = $templateRepository->findOneActive();

        // Set default template, first active from table
        if ($template instanceof Template) {
            $post->setTemplate($template);
        }

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formPost = $form->getData();

            $entityManager->persist($formPost);
            $entityManager->flush();

            $this->addFlash('success', 'Post successfully created');

            return $this->redirectToRoute('app_post_details', ['id' => $formPost->getId()]);
        }

        return $this->render('post/new.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/blog/{id}/details', name: 'app_post_details')]
    public function details(Post $post): Response
    {
        return $this->render('post/details.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/blog/{id}/update', name: 'app_post_update')]
    public function update(Request $request, EntityManagerInterface $entityManager, Post $post): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formPost = $form->getData();

            $entityManager->persist($formPost);
            $entityManager->flush();

            $this->addFlash('success', 'Post Updated Successfully');

            return $this->redirectToRoute('app_post_details', ['id' => $formPost->getId()]);
        }

        return $this->render('post/update.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    #[Route('/post-values-form/{systemName}', name: 'app_post_values_form')]
    public function postValuesForm(TemplateRepository $templateRepository, Request $request, string $systemName): Response
    {
        $template = $templateRepository->findBySystemName($systemName);
        if (!$template instanceof Template) {
            throw $this->createNotFoundException('Template not found');
        }

        $post = new Post();
        $post->setTemplate($template);

        $form = $this->createForm(PostType::class, $post);

        return $this->render('post/_form_rest.html.twig', [
            'form' => $form,
        ]);
    }
}
