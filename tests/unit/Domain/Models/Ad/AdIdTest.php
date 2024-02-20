<?php

use AjaxSnippets\Api\Domain\Models\Ad\AdId;

class AdIdTest extends WP_UnitTestCase
{
  public function testAdId()
  {
    $adId = new AdId(1);
    $this->assertEquals(1, $adId->getId());
  }

  public function testAdIdNull()
  {
    $adId = new AdId();
    $this->assertEquals(0, $adId->getId());
  }
}