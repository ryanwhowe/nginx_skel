<?php

namespace App\Controller;

use App\Entity\IssueStatus;
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

    #[Route('/approved', name: 'approved')]
    public function new(): Response {
        return $this->render('main/approved.html.twig', $this->baseResponse());
    }

    #[Route('/tasks', name: 'tasks')]
    public function tasks(): Response {
        return $this->render('main/tasks.html.twig', $this->baseResponse());
    }

    #[Route('/personal', name: 'personal')]
    public function personal(): Response {
        return $this->render('main/personal.html.twig', $this->baseResponse());
    }

    protected function baseResponse(): array {
        return [
            'redmine_proxy_url' => $this->generateUrl('app_utility_redmine', ['data' => '']),
            'redmine_url' => $this->getParameter('app.redmine_url'),
            'redmine_user_id' => $this->getParameter('app.redmine_user_id'),
            'redmine_cr_user_name' => $this->getParameter('app.redmine_cr_user_name'),
            'redmine_statuses' => [
                'STATUS_BUSINESS_REVIEW' => IssueStatus::STATUS_BUSINESS_REVIEW,
                'STATUS_ASSIGNED' => IssueStatus::STATUS_ASSIGNED,
                'STATUS_QA_REVIEW' => IssueStatus::STATUS_QA_REVIEW,
                'STATUS_CLOSED' => IssueStatus::STATUS_CLOSED,
                'STATUS_NA2' => IssueStatus::STATUS_NA2,
                'STATUS_NA3' => IssueStatus::STATUS_NA3,
                'STATUS_NA1' => IssueStatus::STATUS_NA1,
                'STATUS_CODE_REVIEW' => IssueStatus::STATUS_CODE_REVIEW,
                'STATUS_APPROVED' => IssueStatus::STATUS_APPROVED,
                'STATUS_RELEASE_HOLD' => IssueStatus::STATUS_RELEASE_HOLD,
                'STATUS_BACKLOG' => IssueStatus::STATUS_BACKLOG,
            ]
        ];
    }
}
