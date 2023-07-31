<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/', name: 'app_main_')]
class MainController extends AbstractController {

    #[Route('/', name: 'index')]
    public function index(): Response {
        return $this->render('main/index.html.twig', $this->baseResponse());
    }

    #[Route('/codeReview', name: 'codeReview')]
    public function codeReview(): Response {
        return $this->render('main/codeReview.html.twig', $this->baseResponse());
    }

    #[Route('/releaseHold', name: 'releaseHold')]
    public function releaseHold(): Response {
        return $this->render('main/releaseHold.html.twig', $this->baseResponse());
    }

    #[Route('/new', name: 'new')]
    public function new(): Response {
        return $this->render('main/new.html.twig', $this->baseResponse());
    }

    #[Route('/tasks', name: 'tasks')]
    public function tasks(): Response {
        return $this->render('main/tasks.html.twig', $this->baseResponse());
    }

    protected function baseResponse(): array {
        return [
            'redmine_proxy_url' => $this->generateUrl('app_utility_redmine', ['data' => '']),
            'redmine_url' => $this->getParameter('app.redmine_url'),
            'redmine_user_id' => $this->getParameter('app.redmine_user_id'),
            'redmine_cr_user_name' => $this->getParameter('app.redmine_cr_user_name')
        ];
    }
}
