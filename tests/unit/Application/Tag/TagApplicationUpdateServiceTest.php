<?php

use AjaxSnippets\Api\Application\Tag\TagUpdateService;
use AjaxSnippets\Api\Domain\Models\Tag\ITagRepository;
use AjaxSnippets\Api\Domain\Models\Tag\TagId;
use AjaxSnippets\Api\Domain\Models\Tag\Tag;
use AjaxSnippets\Api\Application\Tag\TagUpdateCommand;

class TagApplicationUpdateServiceTest extends WP_UnitTestCase
{
  private ITagRepository $tagRepository;
  private TagUpdateService $tagUpdateService;
  
  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    parent::setUp();
    $this->tagRepository = $diContainer->get(ITagRepository::class);
    $wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "tag");
    $this->tagUpdateService = new TagUpdateService($this->tagRepository);
  }

  public function testUpdate()
  {
    $this->tagRepository->save(new Tag(new TagId(), 'tagName1', 1));
    $this->tagRepository->save(new Tag(new TagId(), 'tagName2', 2));
    $this->tagRepository->save(new Tag(new TagId(), 'tagName3', 3));
    $this->tagRepository->save(new Tag(new TagId(), 'tagName4', 4));

    $request = new \WP_REST_Request();
    $request->set_param('id', 4);
    $request->set_param('tagName', 'tagName100');
    $request->set_param('tagOrder', 100);
    $command = new TagUpdateCommand($request);
    $tagId = $this->tagUpdateService->handle($command);
    $this->assertEquals(new TagId(4), $tagId);

    // きちんと更新されたか確認
    $res = $this->tagRepository->findById(new TagId(4));
    $expected = new Tag(new TagId(4), 'tagName100', 100);
    $this->assertEquals($expected, $res);
  }
}