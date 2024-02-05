<?php

use AjaxSnippets\Api\Application\Asp\AspDeleteService;
use AjaxSnippets\Api\Domain\Models\Asp\IAspRepository;
use AjaxSnippets\Api\Domain\Models\Asp\AspId;
use AjaxSnippets\Api\Domain\Models\Asp\Asp;
use AjaxSnippets\Api\Application\Asp\AspDeleteCommand;

class AspApplicationDeleteServiceTest extends WP_UnitTestCase
{
  private IAspRepository $aspRepository;
  private AspDeleteService $aspDeleteService;

  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    parent::setUp();
    $this->aspRepository = $diContainer->get(IAspRepository::class);
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "asp");
    $this->aspDeleteService = new AspDeleteService($this->aspRepository);
  }

  public function testdelete()
  {
    $aspId = $this->aspRepository->save(new Asp(new AspId(), 'aspName1', 'connectString1'));
    
    $request = new \WP_REST_Request();
    $request->set_param('id', $aspId->getId());
    $command = new AspDeleteCommand($request);
    $result = $this->aspDeleteService->handle($command);
    $this->assertTrue($result);
  }
}