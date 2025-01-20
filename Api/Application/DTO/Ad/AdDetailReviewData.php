<?php

namespace AjaxSnippets\Api\Application\DTO\Ad;

use AjaxSnippets\Api\Domain\Models\Ad\Ad;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailReview;

class AdDetailReviewData
{

  public function __construct(private array $reviews = []) {}

  public function handle(): object
  {
    $availableReviews = collect($this->reviews)->filter(function ($review) {
      return $review->getIsPublished() === true;
    })->toArray();
    return (object)[
      'ratingValue' => collect($availableReviews)->sum(fn($review) => $review->getRatingValue()),
      'bestRating' => count($availableReviews) * 5, // 5 is the maximum rating　修正が必要
      'ratingCount' => count($availableReviews),
      'reviews' => collect($availableReviews)
        ->map(function ($review) {
          return $this->getReviewCard($review);
        })->values()->all()
    ];
  }

  private function getReviewCard(AdDetailReview $review): object
  {
    return (object)[
      'ratingValue' => $review->getRatingValue(),
      'name'  => $review->getName(),
      'age'   => $review->getAge(),
      'sex' => $review->getSex(),
      'content' => $review->getContent(),
      'quoteName' => $review->getQuoteName(),
      'quoteUrl' => $review->getQuoteUrl()
    ];
  }
}
