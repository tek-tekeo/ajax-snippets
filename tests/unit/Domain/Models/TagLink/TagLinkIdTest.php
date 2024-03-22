<?php
use AjaxSnippets\Api\Domain\Models\TagLink\TagLinkId;

class TagLinkIdTest extends WP_UnitTestCase
{

  public function testTagLinkId()
  {
    $tagLinkId = new TagLinkId(1);
    $this->assertEquals(1, $tagLinkId->getId());
  }

  public function testTagLinkIdNull()
  {
    $tagLinkId = new TagLinkId();
    $this->assertEquals(0, $tagLinkId->getId());
  }
}