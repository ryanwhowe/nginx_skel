<?php

namespace App\Controller;

use App\Entity\Flash;
use App\Entity\IssueStatus;
use Monolog\Level;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/api', name: 'app_api_')]
class ApiController extends AbstractController {

    #[Route('/codeReview/{id}/{type}', name: 'codeReviewComplete')]
    public function codeReview(string $id, string $type, HttpClientInterface $client, LoggerInterface $logger): RedirectResponse|JsonResponse {

        $type = (int)$type;
        $id = (int)$id;

        $time_entity_activity_support = 10; // Support
        $time_entity_hours = 0.25;

        // Update the passed in ticket status to in progress and add a comment that the Code Review Complete

        $url = $this->getParameter('app.redmine_url');
        $url .= "issues/${id}.json";

        $issue_data = [
            'issue' => [
                'status_id' => IssueStatus::STATUS_ASSIGNED,
                'custom_fields' => [[
                    'id' => 3,
                    'value' => $this->getParameter('app.redmine_cr_user_name')
                ]],
                'notes' => ($type === 0) ? 'Code Review Complete' : 'Code Review Complete, Pushed to Demo'
            ]
        ];

        $logger->log(Level::Debug, 'url constructed: ' . $url, $issue_data);
        try {
            $response = $client->request('PUT', $url, [
                'headers' => [
                    'X-Redmine-API-Key' => $this->getParameter('app.redmine_api_token')
                ],
                'body' => $issue_data
            ]);
            $response->getContent(false);
        } catch (TransportExceptionInterface $e) {
            return new JsonResponse($e->getTrace(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        // log time against the ticket of 0.25 hours
        $url = $this->getParameter('app.redmine_url');
        $url .= "time_entries.json";

        $time_entry_data = [
            'time_entry'=>[
                'issue_id' => $id,
                'user_id' => $this->getParameter('app.redmine_user_id'),
                'hours' => $time_entity_hours,
                'activity_id' => $time_entity_activity_support,
                'comments' => ($type === 0) ? 'Code Review Complete' : 'Code Review Complete, Pushed to Demo'
            ]
        ];

        $logger->log(Level::Debug,'url constructed: ' . $url, $time_entry_data);
        try {
            $response = $client->request('POST', $url, [
                'headers' => [
                    'X-Redmine-API-Key' => $this->getParameter('app.redmine_api_token')
                ],
                'body' => $time_entry_data
            ]);
            $response->getContent(false);
        } catch (TransportExceptionInterface $e) {
            return new JsonResponse($e->getTrace(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $url = $this->getParameter('app.redmine_url');
        $url .= 'issues/' . $id . '/edit';
        $logger->debug('redirecting to ' . $url);
        return new RedirectResponse($url);
    }

    #[Route('/release/{id}', name: 'released')]
    public function release(string $id, HttpClientInterface $client, LoggerInterface $logger): RedirectResponse|JsonResponse {

        $id = (int)$id;
        $url = $this->getParameter('app.redmine_url');
        $url .= "issues/${id}.json";
        $issue_data = [
            'issue' => [
                'status_id' => IssueStatus::STATUS_CLOSED,
                'notes' => 'Released'
            ]
        ];

        $logger->log(Level::Debug, 'url constructed: ' . $url, $issue_data);
        try {
            $response = $client->request('PUT', $url, [
                'headers' => [
                    'X-Redmine-API-Key' => $this->getParameter('app.redmine_api_token')
                ],
                'body' => $issue_data
            ]);
            $response->getContent(false);
        } catch (TransportExceptionInterface $e) {
            return new JsonResponse($e->getTrace(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $this->addFlash(Flash::ALERT_SUCCESS, "Issue:$id has been set to Closed");
        return $this->redirectToRoute('app_main_releaseHold');

    }

}