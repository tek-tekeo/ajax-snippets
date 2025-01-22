<?php

use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailReview;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailReviewRepository;

class AdDetailReviewServiceTest extends WP_UnitTestCase
{

  private $adDetailReviewRepository;

  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    parent::setUp();
    $this->adDetailReviewRepository = $diContainer->get(IAdDetailReviewRepository::class);
  }

  public function testExistsAdDetailReview()
  {
    $adDetailReview = new AdDetailReview(
      0,
      new AdDetailId(1),
      'name',
      10,
      'sex',
      4.4,
      'title',
      'content',
      'quoteName',
      'quoteUrl',
      false
    );

    $this->adDetailReviewRepository->save($adDetailReview);

    $this->assertTrue($this->adDetailReviewRepository->existByContent($adDetailReview->getContent()));
  }
}
