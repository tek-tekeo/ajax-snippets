
<?php
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailReview;

final class AdDetailReviewTest extends WP_UnitTestCase
{

  public function testAdDetailReview()
  {
    $review = new AdDetailReview(
      0,
      new AdDetailId(1),
      '匿名',
      20,
      '男性',
      4.5,
      'コンテンツ',
      'google',
      'https://google.com',
      true
    );
    $this->assertEquals(
      [
        'id' => 0,
        'ad_detail_id' => 1,
        'name' => '匿名',
        'sex' => '男性',
        'age' => 20,
        'rate' => 4.5,
        'content' => 'コンテンツ',
        'quote_name' => 'google',
        'quote_url' => 'https://google.com',
        'is_published' => true,
        'created_at' => '',
        'updated_at' =>''
      ], $review->entity());
  }
}