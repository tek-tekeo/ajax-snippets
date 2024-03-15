
<?php
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailInfo;

final class AdDetailInfoTest extends WP_UnitTestCase
{

  public function testAdDetailInfo()
  {
    $chart = new AdDetailInfo(0, new AdDetailId(1), 'URL', 'https://sample.com');
    $this->assertEquals(
      [
        'id' => 0,
        'ad_detail_id' => 1,
        'title' => 'URL',
        'content' => 'https://sample.com',
        'sort_order' => 0,
      ], $chart->entity());
  }
}