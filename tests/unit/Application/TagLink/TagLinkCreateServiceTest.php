<?php

use AjaxSnippets\Api\Application\TagLink\TagLinkCreateService;
use AjaxSnippets\Api\Domain\Models\TagLink\ITagLinkRepository;
use AjaxSnippets\Api\Domain\Models\TagLink\TagLinkId;
use AjaxSnippets\Api\Application\TagLink\TagLinkCreateCommand;

class TagLinkCreateServiceTest extends WP_UnitTestCase
{
  private ITagLinkRepository $tagLinkRepository;
  private TagLinkCreateService $tagLinkCreateService;

  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    parent::setUp();
    $this->tagLinkRepository = $diContainer->get(ITagLinkRepository::class);
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "tag_link");
    $this->tagLinkCreateService = new TagLinkCreateService($this->tagLinkRepository);
  }

  public function testCreate()
  {
    $request = new \WP_REST_Request();
    $request->set_param('tagId', 1);
    $request->set_param('adDetailId', 1);

    $cmd = new TagLinkCreateCommand($request);
    // 新規登録されたら登録IDが返る
    $tagLinkId = $this->tagLinkCreateService->handle($cmd);
    $this->assertEquals(new TagLinkId(1), $tagLinkId);

    // 同じタグ名で登録されたら登録失敗となる
    // $this->expectException(\Exception::class);
    // $this->expectExceptionMessage('tagLink already exists');
    // $this->expectExceptionCode(500);
    // $tagLinkId = $this->tagLinkCreateService->handle($command);
  }
}