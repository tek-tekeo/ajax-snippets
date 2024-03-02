<?php

use AjaxSnippets\Api\Application\Tag\TagGetService;
use AjaxSnippets\Api\Application\DTO\TagData;
use AjaxSnippets\Api\Domain\Models\Tag\ITagRepository;
use AjaxSnippets\Api\Domain\Models\Tag\TagId;
use AjaxSnippets\Api\Domain\Models\Tag\Tag;
use AjaxSnippets\Api\Application\Tag\TagGetCommand;
use AjaxSnippets\Api\Application\Tag\TagSearchCommand;

class TagApplicationGetServiceTest extends WP_UnitTestCase
{
  private ITagRepository $tagRepository;
  private TagGetService $tagGetService;

  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    parent::setUp();
    $this->tagRepository = $diContainer->get(ITagRepository::class);
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "tag");
    $this->tagGetService = new TagGetService($this->tagRepository);
  }

  public function testGetTag()
  {
    $this->tagRepository->save(new Tag(new TagId(1), 'tagName1', 1));
    $this->tagRepository->save(new Tag(new TagId(2), 'tagName2', 2));

    $tagId = new TagId(1);
    $request = new \WP_REST_Request();
    $request->set_param('id', $tagId->getId());
    $command = new TagGetCommand($request);

    $actualTagData = $this->tagGetService->handle($command);

    $expected = new TagData(new Tag(new TagId(1), 'tagName1', 1));
    $this->assertEquals($expected, $actualTagData);

    // 全てを取得する
    $actualTagData = $this->tagGetService->getAll();
    $expected = [
      new TagData(new Tag(new TagId(1), 'tagName1', 1)),
      new TagData(new Tag(new TagId(2), 'tagName2', 2))
    ];
    $this->assertEquals($expected, $actualTagData);

    // 検索する ヒットする
    $request = new \WP_REST_Request();
    $request->set_param('name', 'tagName');
    $command = new TagSearchCommand($request);
    $actualTagData = $this->tagGetService->search($command);
    $expected = [
      new TagData(new Tag(new TagId(1), 'tagName1', 1)),
      new TagData(new Tag(new TagId(2), 'tagName2', 2))
    ];
    $this->assertEquals($expected, $actualTagData);
    
    // ヒットしない
    $request = new \WP_REST_Request();
    $request->set_param('name', 'tagName3');
    $command = new TagSearchCommand($request);
    $actualTagData = $this->tagGetService->search($command);
    $expected = [];
    $this->assertEquals($expected, $actualTagData);
    
  }

}