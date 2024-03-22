<?php

use AjaxSnippets\Api\Application\Asp\AspCreateService;
use AjaxSnippets\Api\Domain\Models\Asp\IAspRepository;
use AjaxSnippets\Api\Domain\Models\Asp\AspId;
use AjaxSnippets\Api\Application\Asp\AspCreateCommand;
use AjaxSnippets\Api\Domain\Services\AspService;

class AspApplicationCreateServiceTest extends WP_UnitTestCase
{
  private IAspRepository $aspRepository;
  private AspCreateService $aspCreateService;
  private AspService $aspService;
  private $table;

  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    $this->table = PLUGIN_DB_PREFIX . 'asps';
    parent::setUp();
    $this->aspRepository = $diContainer->get(IAspRepository::class);
    $this->aspService = new AspService($this->aspRepository);
		$wpdb->query("TRUNCATE TABLE " . $this->table);
    $this->aspCreateService = new AspCreateService($this->aspService, $this->aspRepository);
  }

  public function test_create()
  {
    $request = new \WP_REST_Request();
    $request->set_param('aspName', 'aspName');
    $request->set_param('connectString', 'connectString');

    $command = new AspCreateCommand($request);
    // 新規登録されたら登録IDが返る
    $aspId = $this->aspCreateService->handle($command);
    $this->assertEquals(new AspId(1), $aspId);

    // 同じASP名で登録されたら登録失敗となる
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('asp already exists');
    $this->expectExceptionCode(500);
    $aspId = $this->aspCreateService->handle($command);
  }
}