<?php

use AjaxSnippets\Api\Application\TagLink\TagLinkUpdateService;
use AjaxSnippets\Api\Domain\Models\TagLink\ITagLinkRepository;
use AjaxSnippets\Api\Domain\Models\TagLink\TagLinkId;
use AjaxSnippets\Api\Domain\Models\TagLink\TagLink;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\Tag\TagId;
use AjaxSnippets\Api\Application\TagLink\TagLinkUpdateCommand;

class TagLinkUpdateServiceTest extends WP_UnitTestCase
{
  private ITagLinkRepository $tagLinkRepository;
  private TagLinkUpdateService $tagLinkUpdateService;
  
  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    parent::setUp();
    $this->tagLinkRepository = $diContainer->get(ITagLinkRepository::class);
    $wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "tag_link");
    $this->tagLinkUpdateService = new TagLinkUpdateService($this->tagLinkRepository);
  }
  
  public function testTrue()
  {
    $this->assertTrue(true);
  }
  
  public function estUpdate()
  {
    $this->tagLinkRepository->save(
      new TagLink(
        new TagLinkId(),
        new AdDetailId(1),
        new TagId(1)
      )
    );
    $this->tagLinkRepository->save(
      new TagLink(
        new TagLinkId(),
        new AdDetailId(1),
        new TagId(2)
      )
    );
    $this->tagLinkRepository->save(
      new TagLink(
        new TagLinkId(),
        new AdDetailId(2),
        new TagId(2)
      )
    );

    $request = new \WP_REST_Request();
    $request->set_param('adDetailId', 2);
    $request->set_param('tagIds', [1, 2]);
    $command = new TagLinkUpdateCommand($request);
    $tagIds = $this->tagLinkUpdateService->handle($command);
    $this->assertEquals([new TagLinkId(4), new TagLinkId(5)], $tagIds);

    // きちんと更新されたか確認
    $res = $this->tagLinkRepository->findByAdDetailId(new AdDetailId(2));
    $expected = [
      new TagLink(new TagLinkId(4), new AdDetailId(2), new TagId(1)),
      new TagLink(new TagLinkId(5), new AdDetailId(2), new TagId(2)),
    ];
    $this->assertEquals($expected, $res);
  }
}