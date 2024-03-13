<?php

use AjaxSnippets\Api\Application\Log\LogGetService;
use AjaxSnippets\Api\Application\DTO\Log\LogData;
use AjaxSnippets\Api\Application\DTO\Log\ClickData;
use AjaxSnippets\Api\Application\DTO\Log\DayPerClickData;
use AjaxSnippets\Api\Domain\Models\Log\ILogRepository;
use AjaxSnippets\Api\Domain\Models\Log\LogId;
use AjaxSnippets\Api\Domain\Models\Log\Log;
use AjaxSnippets\Api\Application\Log\LogGetCommand;
use AjaxSnippets\Api\Application\Log\LogSearchCommand;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;

class LogGetServiceTest extends WP_UnitTestCase
{
  private ILogRepository $logRepository;
  private LogGetService $logGetService;
  private Log $log;
  private array $columns;

  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    parent::setUp();
    $this->logRepository = $diContainer->get(ILogRepository::class);
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "log");
    $this->logGetService = new LogGetService($this->logRepository);
    $this->columns = [
      new AdDetailId(1),
      '2024-08-01',
      '20:00:00',
      'place',
      'ip',
      'post_addr'
    ];
    $this->log = new Log(
      new LogId(),
      ...$this->columns
    );
  }

  public function testGetLog()
  {
    $this->logRepository->save($this->log);
    $this->logRepository->save($this->log);
    $this->logRepository->save($this->log);

    $request = new \WP_REST_Request();
    $request->set_param('dates', ['2024-07-01', '2024-08-03']);
    $request->set_param('limit', 2);
    $command = new LogGetCommand($request);
    $actualLogData = $this->logGetService->handle($command);
    
    $expected = [
      new LogData(new Log(new LogId(1), ...$this->columns)),
      new LogData(new Log(new LogId(2), ...$this->columns))
    ];
    $this->assertEquals($expected, $actualLogData);

    // 記事別クリック数
    $actualLogData = $this->logGetService->getArticleCountLogs($command);
    $expected = [];
    $expected[0] = 'post_addr';
    $expected[1] = 'place';
    $expected[2] = 3;
    $this->assertEquals([new ClickData(...$expected)], $actualLogData);

    // 商品別クリック数
    $actualLogData = $this->logGetService->getItemCountLogs($command);
    $expected = [];
    $expected[0] = 'ad name';
    $expected[1] = 'place';
    $expected[2] = 3;
    $this->assertEquals([new ClickData(...$expected)], $actualLogData);

    // 日別クリック数
    $actualLogData = $this->logGetService->getDayPerClick($command);
    $expected = new stdClass;
    $expected->date = '2024-08-01';
    $expected->clicks = 1;
    $this->assertEquals([new DayPerClickData($expected)], $actualLogData);
    
  }

}