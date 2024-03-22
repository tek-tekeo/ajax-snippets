<?php
use AjaxSnippets\Api\Domain\Models\TagLink\TagLinkId;
use AjaxSnippets\Api\Domain\Models\TagLink\TagLink;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\Tag\TagId;
use AjaxSnippets\Api\Infrastructure\Repository\TagLinkRepository;

final class TagLinkRepositoryTest extends WP_UnitTestCase
{
  private $repository;

	public function setUp():void
	{
		parent::setUp();
		$this->resetDatabase();
		$this->repository = new TagLinkRepository();
	}

	protected function resetDatabase()
	{
		global $wpdb;
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "tag_link");
	}

  public function testSaveTagLink()
  {
    $tagLinkId = new TagLinkId();
    $adDetailId = new AdDetailId(1);
    $tagId = new TagId(1);

    $tagLink = new TagLink(
      $tagLinkId,
      $adDetailId,
      $tagId
    );

    $insertId = $this->repository->save($tagLink);
    $this->assertEquals(new TagLinkId(1), $insertId);
  }

  public function testFindTagLink()
  {
    $tagLink = new TagLink(
      new TagLinkId(1),
      new AdDetailId(1),
      new TagId(1)
    );
    $this->repository->save($tagLink);

    $tagLink = new TagLink(
      new TagLinkId(2),
      new AdDetailId(2),
      new TagId(2)
    );
    $this->repository->save($tagLink);

    $findTagLinkByAdDetailId = $this->repository->findByAdDetailId(new AdDetailId(2));
    $this->assertEquals([
      new TagLink(
        new TagLinkId(2),
        new AdDetailId(2),
        new TagId(2)
      )], $findTagLinkByAdDetailId
    );

    $findTagLinkByTagId = $this->repository->getItemIdsByTag(new TagId(1));
    $this->assertEquals([1], $findTagLinkByTagId
    );
  }

  public function testDeleteTagLink()
  {
    $tagLinkId = new TagLinkId(1);

    $tagLink = new TagLink(
      $tagLinkId,
      new AdDetailId(1),
      new TagId(1)
    );
    $this->repository->save($tagLink);
    $deletedAdDetailId =  new AdDetailId(1);
    $deleteTagLink = $this->repository->delete($deletedAdDetailId);
    $this->assertEquals(true, $deleteTagLink);
  }

}