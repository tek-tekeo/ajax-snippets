<?php
use AjaxSnippets\Api\Domain\Models\Tag\TagId;
use AjaxSnippets\Api\Domain\Models\Tag\Tag;
use AjaxSnippets\Api\Infrastructure\Repository\TagRepository;

final class TagRepositoryTest extends WP_UnitTestCase
{
  private $repository;

	public function setUp():void
	{
		parent::setUp();
		$this->resetDatabase();
		$this->repository = new TagRepository();
	}

	protected function resetDatabase()
	{
		global $wpdb;
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . 'tags');
	}

  public function testSaveTag()
  {
    $tagId = new TagId();
    $tag = new Tag(
      $tagId,
      'Tag Name',
      1
    );

    $insertId = $this->repository->save($tag);
    $this->assertEquals(new TagId(1), $insertId);
  }

  public function testFindTag()
  {
    $tagId = new TagId(1);

    $tag = new Tag(
      $tagId,
      'Tag Name',
      1
    );
    $this->repository->save($tag);

    $findTagById = $this->repository->findById(new TagId(1));
    $this->assertEquals(
      new Tag(
        new TagId(1),
        'Tag Name',
        1
      ), $findTagById
    );
  }

  public function testDeleteTag()
  {
    $tagId = new TagId(1);

    $tag = new Tag(
      $tagId,
      'Tag Name',
      1
    );
    $this->repository->save($tag);

    $deleteTag = $this->repository->delete($tagId);
    $this->assertEquals(true, $deleteTag);
  }

  public function testFindTags()
  {
    $newTagId = new TagId();
    $tag = new Tag(
      $newTagId,
      'Main Tag Name',
      3
    );

    $insertId = $this->repository->save($tag);
    $findTagById = $this->repository->findById($insertId);
    $this->assertEquals(
      new Tag(
        new TagId(1),
        'Main Tag Name',
        3
      ), $findTagById
    );

    $secondAd = new Tag(
      $newTagId,
      'Main Tag Name2',
      4  
    );
    $insertId = $this->repository->save($secondAd);

    // 名前がヒットするものがない場合
    $findTagByName = $this->repository->findByName('Dont find name');
    $this->assertEquals([], $findTagByName);

    // 2件名前がヒットする場合
    $findTagByName = $this->repository->findByName('Main Tag Name');
    $this->assertEquals(2, count($findTagByName));
  }
}