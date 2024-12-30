
<?php

use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;


final class AdDetailTest extends WP_UnitTestCase
{

  public function testAdDetail()
  {
    $adDetailId = new AdDetailId(1);
    $adId = new AdId(1);
    $adDetail = new AdDetail(
      $adDetailId,
      $adId,
      'item name',
      'official item link',
      'affi item link',
      'detail image',
      'amazon asin',
      'rakuten id',
      'review',
      1,
      1
    );
    $this->assertEquals(new AdDetailId(1), $adDetail->getId());
    $this->assertEquals(new AdId(1), $adDetail->getAdId());
    $this->assertEquals('item name', $adDetail->getItemName());

    $this->assertEquals(
      [
        'id' => 1,
        'ad_id' => 1,
        'item_name' => 'item name',
        'official_item_link' => 'official item link',
        'affi_item_link' => 'affi item link',
        'detail_img' => 'detail image',
        'amazon_asin' => 'amazon asin',
        'rakuten_id' => 'rakuten id',
        'review' => 'review',
        'is_show_url' => 1,
        'same_parent' => 1,
        'rakuten_expired_at' => null
      ],
      $adDetail->entity()
    );
  }
}
