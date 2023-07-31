<?php

namespace App\Entity;

class IssueStatus {

  const STATUS_NEW =  1;
  const STATUS_IN_PROGRESS =  2;
  const STATUS_FEEDBACK =  4;
  const STATUS_CLOSED =  5;
  const STATUS_REJECTED =  6;
  const STATUS_BUSINESS_REFINE =  7;
  const STATUS_FEEDBACK_HOLD =  8;
  const STATUS_CODE_REVIEW =  9;
  const STATUS_PENDING = 10;
  const STATUS_RELEASE_HOLD = 13;
  const STATUS_BACKLOG = 14;
}