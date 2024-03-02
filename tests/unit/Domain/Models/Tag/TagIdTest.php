<?php

use AjaxSnippets\Api\Domain\Models\Tag\TagId;

class TagIdTest extends WP_UnitTestCase
{
  public function testTagId()
  {
    $tagId = new TagId(1);
    $this->assertEquals(1, $tagId->getId());
  }

  public function testTagIdNull()
  {
    $tagId = new TagId();
    $this->assertEquals(0, $tagId->getId());
  }
}