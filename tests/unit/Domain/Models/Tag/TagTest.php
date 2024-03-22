<?php
use AjaxSnippets\Api\Domain\Models\Tag\Tag;
use AjaxSnippets\Api\Domain\Models\Tag\TagId;
class TagTest extends WP_UnitTestCase
{
  public function testTag()
  {
    $tagId = new TagId();
    $tag = new Tag($tagId, 'tag', 3);
    $this->assertEquals(new TagId(0), $tag->getId());
    $this->assertEquals('tag', $tag->getName());
    $this->assertEquals(3, $tag->getOrder());
  }

  public function testTagEntity()
  {
    $tagId = new TagId();
    $tag = new Tag($tagId, 'tag', 3);
    $this->assertEquals(array(
      'id' => 0,
      'tag_name' => 'tag',
      'tag_order' => 3
    ), $tag->entity());
  }
}