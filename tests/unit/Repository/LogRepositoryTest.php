<?php
use AjaxSnippets\Api\Domain\Models\Log\LogId;
use AjaxSnippets\Api\Domain\Models\Log\Log;
use AjaxSnippets\Api\Infrastructure\Repository\LogRepository;

final class LogRepositoryTest extends WP_UnitTestCase
{
  private $repository;

	public function setUp():void
	{
		parent::setUp();
		$this->resetDatabase();
		$this->repository = new LogRepository();
	}

	protected function resetDatabase()
	{
		global $wpdb;
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "log");
	}

  public function testRecordLog()
  {
    $logId = new LogId();
    $log = new Log(
      $logId,
      3,
      '2024-08-01',
      '20:00:00',
      'place',
      'ip',
      'post_addr'
    );

    $insertId = $this->repository->save($log);
    $this->assertEquals(new LogId(1), $insertId);
  }

  public function testGetLogs()
  {
    $logId = new LogId();
    $log = new Log(
      $logId,
      3,
      '2024-08-01',
      '20:00:00',
      'place',
      'ip',
      'post_addr'
    );
    $this->repository->save($log);

    $getLogs = $this->repository->getLogs();
    $this->assertEquals(
      [new Log(
        new LogId(1),
        3,
        '2024-08-01',
        '20:00:00',
        'place',
        'ip',
        'post_addr'
      )], $getLogs
    );
  }

  public function testDeleteLog()
  {
    $logId = new LogId(1);

    $log = new Log(
      $logId,
      3,
      '2024-08-01',
      '20:00:00',
      'place',
      'ip',
      'post_addr'
    );
    $this->repository->save($log);

    $deleteLog = $this->repository->delete($logId);
    $this->assertEquals(true, $deleteLog);
  }

  // public function testFindLogs()
  // {
  //   $newLogId = new LogId();
  //   $log = new Log(
  //     $newLogId,
  //     'Main Log Name',
  //     3
  //   );

  //   $insertId = $this->repository->save($log);
  //   $findLogById = $this->repository->findById($insertId);
  //   $this->assertEquals(
  //     new Log(
  //       new LogId(1),
  //       'Main Log Name',
  //       3
  //     ), $findLogById
  //   );

  //   $secondAd = new Log(
  //     $newLogId,
  //     'Main Log Name2',
  //     4  
  //   );
  //   $insertId = $this->repository->save($secondAd);

  //   // 名前がヒットするものがない場合
  //   $findLogByName = $this->repository->findByName('Dont find name');
  //   $this->assertEquals([], $findLogByName);

  //   // 2件名前がヒットする場合
  //   $findLogByName = $this->repository->findByName('Main Log Name');
  //   $this->assertEquals(2, count($findLogByName));
  // }
}