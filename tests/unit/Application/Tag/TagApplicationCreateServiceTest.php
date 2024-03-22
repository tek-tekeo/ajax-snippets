<?php

use AjaxSnippets\Api\Application\Tag\TagCreateService;
use AjaxSnippets\Api\Domain\Models\Tag\ITagRepository;
use AjaxSnippets\Api\Domain\Models\Tag\TagId;
use AjaxSnippets\Api\Application\Tag\TagCreateCommand;

class TagApplicationCreateServiceTest extends WP_UnitTestCase
{
  private ITagRepository $tagRepository;
  private TagCreateService $tagCreateService;

  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    parent::setUp();
    $this->tagRepository = $diContainer->get(ITagRepository::class);
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "tag");
    $this->tagCreateService = new TagCreateService($this->tagRepository);
  }

  public function test_create()
  {
    $request = new \WP_REST_Request();
    $request->set_param('tagName', 'tagName');
    $request->set_param('tagOrder', 1);

    $command = new TagCreateCommand($request);
    // 新規登録されたら登録IDが返る
    $tagId = $this->tagCreateService->handle($command);
    $this->assertEquals(new TagId(1), $tagId);

    // 同じタグ名で登録されたら登録失敗となる
    // $this->expectException(\Exception::class);
    // $this->expectExceptionMessage('tag already exists');
    // $this->expectExceptionCode(500);
    // $tagId = $this->tagCreateService->handle($command);
  }
}