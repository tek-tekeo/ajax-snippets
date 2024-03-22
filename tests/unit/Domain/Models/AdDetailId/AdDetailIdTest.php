<?php

use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;

class AdDetailIdTest extends WP_UnitTestCase
{
  public function testAdDetailIdId()
  {
    $adDetailId = new AdDetailId(1);
    $this->assertEquals(1, $adDetailId->getId());
  }

  public function testAdIdNull()
  {
    $adDetailId = new AdDetailId();
    $this->assertEquals(0, $adDetailId->getId());
  }
}