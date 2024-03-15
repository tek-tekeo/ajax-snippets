<?php

use AjaxSnippets\Api\Domain\Models\Ad\Ad;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Domain\Models\App\AppId;
use AjaxSnippets\Api\Domain\Models\Asp\AspId;

class AdTest extends WP_UnitTestCase
{

  public function testAd()
  {
    $adId = new AdId();
    $ad = new Ad(
      $adId,
      'Main Ad Name',
      'anken-link',
      'https://www.anken.com',
      'https://www.item-link.com',
      new AspId(1),
      'banner-image.jpg',
      'image-tag-url',
      'item-image-tag-url',
      300,
      250,
      new AppId(1)
    );

    $this->assertEquals(
      [
        'id' => 0,
        'name' => 'Main Ad Name',
        'anken' => 'anken-link',
        'affi_link' => 'https://www.anken.com',
        's_link' => 'https://www.item-link.com',
        'asp_id' => 1,
        'affi_img' => 'banner-image.jpg',
        'img_tag' => 'image-tag-url',
        's_img_tag' => 'item-image-tag-url',
        'affi_img_width' => 300,
        'affi_img_height' => 250,
        'app_id' => 1
      ],
      $ad->entity()
    );
  }
}