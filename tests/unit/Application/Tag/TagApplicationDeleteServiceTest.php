<?php

use AjaxSnippets\Api\Application\Tag\TagDeleteService;
use AjaxSnippets\Api\Domain\Models\Tag\ITagRepository;
use AjaxSnippets\Api\Domain\Models\Tag\TagId;
use AjaxSnippets\Api\Domain\Models\Tag\Tag;
use AjaxSnippets\Api\Application\Tag\TagDeleteCommand;

class TagApplicationDeleteServiceTest extends WP_UnitTestCase
{
  private ITagRepository $tagRepository;
  private TagDeleteService $tagDeleteService;

  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    parent::setUp();
    $this->tagRepository = $diContainer->get(ITagRepository::class);
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "tag");
    $this->tagDeleteService = new TagDeleteService($this->tagRepository);
  }

  public function testHandle()
  {
    $aspId = $this->tagRepository->save(new Tag(new TagId(), 'aspName1', 1));
    
    $request = new \WP_REST_Request();
    $request->set_param('id', $aspId->getId());
    $command = new TagDeleteCommand($request);
    $result = $this->tagDeleteService->handle($command);
    $this->assertTrue($result);
  }
}