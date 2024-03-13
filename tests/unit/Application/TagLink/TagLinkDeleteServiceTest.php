<?php

use AjaxSnippets\Api\Application\TagLink\TagLinkDeleteService;
use AjaxSnippets\Api\Domain\Models\TagLink\ITagLinkRepository;
use AjaxSnippets\Api\Domain\Models\TagLink\TagLinkId;
use AjaxSnippets\Api\Domain\Models\TagLink\TagLink;
use AjaxSnippets\Api\Application\TagLink\TagLinkDeleteCommand;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\Tag\TagId;

class TagLinkDeleteServiceTest extends WP_UnitTestCase
{
  private ITagLinkRepository $tagLinkRepository;
  private TagLinkDeleteService $tagLinkDeleteService;

  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    parent::setUp();
    $this->tagLinkRepository = $diContainer->get(ITagLinkRepository::class);
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "tag_link");
    $this->tagLinkDeleteService = new TagLinkDeleteService($this->tagLinkRepository);
  }

  public function testHandle()
  {
    $aspId = $this->tagLinkRepository->save(
      new TagLink(
        new TagLinkId(),
        new AdDetailId(1),
        new TagId(1)
      )
    );
    
    $request = new \WP_REST_Request();
    $request->set_param('id', $aspId->getId());
    $command = new TagLinkDeleteCommand($request);
    $result = $this->tagLinkDeleteService->handle($command);
    $this->assertTrue($result);
  }
}