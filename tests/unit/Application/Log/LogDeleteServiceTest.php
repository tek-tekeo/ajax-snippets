<?php

use AjaxSnippets\Api\Application\Log\LogDeleteService;
use AjaxSnippets\Api\Domain\Models\Log\ILogRepository;
use AjaxSnippets\Api\Domain\Models\Log\LogId;
use AjaxSnippets\Api\Domain\Models\Log\Log;
use AjaxSnippets\Api\Application\Log\LogDeleteCommand;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;

class LogDeleteServiceTest extends WP_UnitTestCase
{
  private ILogRepository $logRepository;
  private LogDeleteService $logDeleteService;

  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    parent::setUp();
    $this->logRepository = $diContainer->get(ILogRepository::class);
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "log");
    $this->logDeleteService = new LogDeleteService($this->logRepository);
  }

  public function testHandle()
  {
    $logId = $this->logRepository->save(new Log(new LogId(), new AdDetailId(3), '2024-08-01', '20:00:00', 'place', 'ip', 'post_addr'));
    
    $request = new \WP_REST_Request();
    $request->set_param('logId', 1);
    $command = new LogDeleteCommand($request);
    $result = $this->logDeleteService->handle($command);
    $this->assertTrue($result);
  }
}