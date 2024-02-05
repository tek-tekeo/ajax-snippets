<?php

use AjaxSnippets\Api\Domain\Models\Asp\AspId;
class AspIdTest extends WP_UnitTestCase
{
  public function testThenAspId()
  {
    $aspId = new AspId(1);
    $this->assertEquals(1, $aspId->getId());
  }

  public function testThenAspIdEqNull()
  {
    $aspId = new AspId();
    $this->assertEquals(0, $aspId->getId());
  }
}