<?php

namespace App\Entity;

class IssueStatus {

    const STATUS_BUSINESS_REVIEW = 1;   // previously STATUS_NEW
    const STATUS_ASSIGNED =  2;         // previously STATUS_IN_PROGRESS
    const STATUS_QA_REVIEW =  4;        // previously STATUS_FEEDBACK
    const STATUS_CLOSED =  5;           // (closed)
    const STATUS_NA2 =  6;              // (closed) Deprecated previously STATUS_REJECTED
    const STATUS_NA3 =  7;              // Deprecated previously STATUS_BUSINESS_REFINE
    const STATUS_NA1 =  8;              // Deprecated previously STATUS_FEEDBACK_HOLD
    const STATUS_CODE_REVIEW =  9;      //
    const STATUS_APPROVED = 10;         // previously STATUS_PENDING
    const STATUS_RELEASE_HOLD = 13;     //
    const STATUS_BACKLOG = 14;          // (closed)

    public static function getMappings(): array {
        return [
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
        ];
    }
}