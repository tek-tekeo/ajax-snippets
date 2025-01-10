<?php

namespace AjaxSnippets\Api\Domain\Services;

use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailReview;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailReviewRepository;

class AdDetailReviewService
{

  public function __construct(
    private IAdDetailReviewRepository $adDetailReviewRepository
  ) {}

  public function exists(AdDetailReview $adDetailReview): bool
  {
    return (bool)$this->adDetailReviewRepository->existByContent($adDetailReview->getContent());
  }
}
