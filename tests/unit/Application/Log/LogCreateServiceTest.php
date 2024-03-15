<?php

use AjaxSnippets\Api\Domain\Models\Log\LogId;
use AjaxSnippets\Api\Domain\Models\Log\ILogRepository;
use AjaxSnippets\Api\Application\Log\LogCreateCommand;
use AjaxSnippets\Api\Application\Log\LogCreateService;

class LogCreateServiceTest extends WP_UnitTestCase
{
  private ILogRepository $logRepository;
  private LogCreateService $logCreateService;
  private \WP_REST_Request $req;
  private $table;

  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    $this->table = PLUGIN_DB_PREFIX . 'logs';
    parent::setUp();
    $this->logRepository = $diContainer->get(ILogRepository::class);
		$wpdb->query("TRUNCATE TABLE " . $this->table);
    $this->logCreateService = new LogCreateService($this->logRepository);

    // ログの情報
    $this->req = new \WP_REST_Request();
    // $this->req->set_param('id', 0);
    $this->req->set_param('adDetailId', 1);
    $this->req->set_param('date', '2024-03-01');
    $this->req->set_param('time', '10:00:00');
    $this->req->set_param('place', 'place');
    $this->req->set_param('ip', '98732792fa');
    $this->req->set_param('postAddr', '');
  }

  public function testCreateCommand()
  {
    $cmd = new LogCreateCommand($this->req);
    $this->assertEquals(1, $cmd->getAdDetailId());
    $this->assertEquals('place', $cmd->getPlace());
  }

  public function test_create()
  {
    $cmd = new LogCreateCommand($this->req);
    // 新規登録されたら登録IDが返る
    $logId = $this->logCreateService->handle($cmd);
    $this->assertEquals(new LogId(1), $logId);

    // 同じlog名で登録されたら登録失敗となる // 実装していない
    // $this->expectException(\Exception::class);
    // $this->expectExceptionMessage('log alrelogy exists');
    // $this->expectExceptionCode(500);
    // $logId = $this->logCreateService->handle($cmd);
  }
}
