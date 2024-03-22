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
  private $table;

  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    parent::setUp();
    $wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ads");
    $wpdb->insert(PLUGIN_DB_PREFIX . "ads",[
      'id' => 1,
      'name' => 'ファイテン',
      'anken' => 'phiten',
      'affi_link' => 'https://px.a8.net/svt/ejp?a8mat=3TNU86+EEKKOI+4VTQ+C0B9T',
      's_link' => 'https://px.a8.net/svt/ejp?a8mat=35SE0F+1AG0WQ+2SIW+BW8O2',
      'asp_id' => 1,
      'affi_img' => 'https://www20.a8.net/svt/bgt?aid=191121135078&wid=001&eno=01&mid=s00000013028001013000&mc=1',
      'img_tag' => 'https://www12.a8.net/0.gif?a8mat=35SE0F+1AFTYQ+2SIW+614CX',
      's_img_tag' => 'https://www16.a8.net/0.gif?a8mat=35SE0F+1AG0WQ+2SIW+BW8O2',
      'affi_img_width' => 300,
      'affi_img_height' => 250,
      'app_id' => 1
    ]);
    $wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ad_details");
    $wpdb->insert(PLUGIN_DB_PREFIX . "ad_details", [
      'id' => 1,
      'ad_id' => 1,
      'item_name' => '商品名',
      'official_item_link' => '公式商品リンク',
      'affi_item_link' => 'アフィリ商品リンク',
      'detail_img' => 'https://www.example.com',
      'amazon_asin' => 'アマゾンASIN',
      'rakuten_id' => '楽天ID',
      'rchart' => '評価チャート',
      'info' => '商品情報',
      'review' => '商品レビュー',
      'is_show_url' => 1,
      'same_parent' => 1
    ]);
    $this->table = PLUGIN_DB_PREFIX . 'logs';
    $this->logRepository = $diContainer->get(ILogRepository::class);
		$wpdb->query("TRUNCATE TABLE " . $this->table);
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
      new LogData(new Log(new LogId(1), ...$this->columns), 'ファイテン 商品名'),
      new LogData(new Log(new LogId(2), ...$this->columns), 'ファイテン 商品名')
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
    $expected[0] = 'ファイテン 商品名';
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