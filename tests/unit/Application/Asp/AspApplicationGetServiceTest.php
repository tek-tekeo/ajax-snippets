<?php

use AjaxSnippets\Api\Application\Asp\AspGetService;
use AjaxSnippets\Api\Application\DTO\AspData;
use AjaxSnippets\Api\Domain\Models\Asp\IAspRepository;
use AjaxSnippets\Api\Domain\Models\Asp\AspId;
use AjaxSnippets\Api\Domain\Models\Asp\Asp;
use AjaxSnippets\Api\Application\Asp\AspGetCommand;

class AspApplicationGetServiceTest extends WP_UnitTestCase
{
  private IAspRepository $aspRepository;
  private AspGetService $aspGetService;

  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    parent::setUp();
    $this->aspRepository = $diContainer->get(IAspRepository::class);
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "asp");
    $this->aspGetService = new AspGetService($this->aspRepository);
  }

  public function testGetAsp()
  {
    $aspId = new AspId(1);
    $this->aspRepository->save(new Asp(new AspId(), 'aspName', 'connectString'));

    $request = new \WP_REST_Request();
    $request->set_param('id', $aspId->getId());
    $command = new AspGetCommand($request);

    $actualAspData = $this->aspGetService->handle($command);

    $expected = new AspData(new Asp(new AspId(1), 'aspName', 'connectString'));
    $this->assertEquals($expected, $actualAspData);
  }

}