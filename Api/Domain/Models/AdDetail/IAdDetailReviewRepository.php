<?php
namespace AjaxSnippets\Api\Domain\Models\AdDetail;

use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailReview;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;

interface IAdDetailReviewRepository
{
  public function save(AdDetailReview $adDetailReview) : int;
}