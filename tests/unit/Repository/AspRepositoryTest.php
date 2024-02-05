<?php

use AjaxSnippets\Api\Domain\Models\Asp\Asp;
use AjaxSnippets\Api\Domain\Models\Asp\AspId;
use AjaxSnippets\Api\Infrastructure\Repository\AspRepository;
class AspRepositoryTest extends WP_UnitTestCase
{
	private $repository;

	public function setUp():void
	{
		parent::setUp();
		$this->resetDatabase();
		$this->repository = new AspRepository();
	}

	protected function resetDatabase()
	{
		global $wpdb;
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "asp");
	}

	public function testCRUDAsp()
	{
		// 新規作成
		$expectedAspId = new AspId(1);
		$create = new Asp(new AspId(), 'a8', '&a8ejpredirect=');
		$aspId = $this->repository->save($create);
		$this->assertEquals($expectedAspId, $aspId);

		// 取得
		$expectedAsp = new Asp(new AspId(1), 'a8', '&a8ejpredirect=');
		$aspId = new AspId(1);
		$asp = $this->repository->get($aspId);
		$this->assertInstanceOf(Asp::class, $asp);
		$this->assertEquals($expectedAsp, $asp);

		// 取得できない
		$aspId = new AspId(99);
		$asp = $this->repository->get($aspId);
		$this->assertFalse($asp);

		// 更新
		$expectedAsp = new Asp(new AspId(1), 'asp2', 'connectionString2');
		$aspId = new AspId(1);
		$asp = new Asp($aspId, 'asp2', 'connectionString2');
		$updateId = $this->repository->save($asp);
		$this->assertEquals($aspId, $updateId);
		$update = $this->repository->get($updateId);
		$this->assertEquals($expectedAsp, $update);

		// 削除
		$aspId = new AspId(1);
		$result = $this->repository->delete($aspId);
		$this->assertTrue($result);
	}

	public function tearDown():void
	{
		parent::tearDown();
	}

}
