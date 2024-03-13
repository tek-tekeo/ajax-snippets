<?php
use AjaxSnippets\Api\Domain\Models\TagLink\TagLink;
use AjaxSnippets\Api\Domain\Models\TagLink\TagLinkId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\Tag\TagId;
class TagLinkTest extends WP_UnitTestCase
{
  public function testTagLink()
  {
    $tagLinkId = new TagLinkId();
    $tagId = new TagId(1);
    $adDetailId = new AdDetailId(1);
    $tagLink = new TagLink($tagLinkId, $adDetailId, $tagId);
    $this->assertEquals(new TagLinkId(0), $tagLink->getId());
    $this->assertEquals(new AdDetailId(1), $tagLink->getAdDetailId());
    $this->assertEquals(new TagId(1), $tagLink->getTagId());
  }

  public function testTagLinkEntity()
  {
    $tagLinkId = new TagLinkId();
    $tagId = new TagId(1);
    $adDetailId = new AdDetailId(1);
    $tagLink = new TagLink($tagLinkId, $adDetailId, $tagId);
    $this->assertEquals(array(
      'id' => 0,
      'ad_detail_id' => 1,
      'tag_id' => 1
    ), $tagLink->entity());
  }
}