<?php

use AjaxSnippets\Api\Domain\Models\Asp\Asp;
use AjaxSnippets\Api\Domain\Models\Asp\AspId;
use AjaxSnippets\Api\Infrastructure\Repository\AspRepository;
class AspRepositoryTest extends WP_UnitTestCase
{
	private $repository;
	private $db;
	private $table;

	public function setUp():void
	{
		parent::setUp();
		$this->resetDatabase();
		$this->repository = new AspRepository();
	}

	protected function resetDatabase()
	{
		global $wpdb;
		$this->db = $wpdb;
		$this->table = PLUGIN_DB_PREFIX . 'asps';
		$this->db->query("TRUNCATE TABLE " . $this->table);
		$this->db->insert($this->table, [
			'id' => 1,
			'asp_name' => 'a8',
			'connect_string' => '&a8ejpredirect='
		]);
		$this->db->insert($this->table, [
			'id' => 2,
			'asp_name' => 'afb',
			'connect_string' => ''
		]);
		$this->db->insert($this->table, [
			'id' => 3,
			'asp_name' => 'dmm',
			'connect_string' => '?af_id=tekeo-001&ch=link_tool&ch_id=link&lurl=',
			'deleted_at' => '2024-01-01'
		]);
		$this->db->insert($this->table, [
			'id' => 4,
			'asp_name' => 'felmat',
			'connect_string' => ''
		]);
	}

	public function testCRUDAsp()
	{
		// 新規作成
		$expectedAspId = new AspId(5);
		$create = new Asp(new AspId(), 'a8', '&a8ejpredirect=');
		$aspId = $this->repository->save($create);
		$this->assertEquals($expectedAspId, $aspId);
		$res = $this->db->get_row("SELECT * FROM " . $this->table . " WHERE id = 1");
		$this->assertEquals([
			'id' => '1',
			'asp_name' => 'a8',
			'connect_string' => '&a8ejpredirect=',
			'deleted_at' => null
		], (array)$res);

		// 取得
		$expectedAsp = new Asp(new AspId(1), 'a8', '&a8ejpredirect=');
		$aspId = new AspId(1);
		$asp = $this->repository->findById($aspId);
		$this->assertInstanceOf(Asp::class, $asp);
		$this->assertEquals($expectedAsp, $asp);

		// 論理削除されているので取得できない
		$aspId = new AspId(3);
		$asp = $this->repository->findById($aspId);
		$this->assertFalse($asp);

		// 更新
		$expectedAsp = new Asp(new AspId(1), 'asp2', 'connectionString2');
		$aspId = new AspId(1);
		$asp = new Asp($aspId, 'asp2', 'connectionString2');
		$updateId = $this->repository->save($asp);
		$this->assertEquals($aspId, $updateId);
		 // 更新の確認
		$res = $this->db->get_row("SELECT * FROM " . $this->table . " WHERE id = 1");
		$this->assertEquals([
			'id' => '1',
			'asp_name' => 'asp2',
			'connect_string' => 'connectionString2',
			'deleted_at' => null
		], (array)$res);

		// 削除
		$aspId = new AspId(1);
		$result = $this->repository->delete($aspId);
		$this->assertTrue($result);
		 // 論理削除の確認
		$res = $this->db->get_row("SELECT * FROM " . $this->table . " WHERE id = 1");
		$this->assertEquals([
			'id' => '1',
			'asp_name' => 'asp2',
			'connect_string' => 'connectionString2',
			'deleted_at' => date('Y-m-d')
		], (array)$res);
	}

	public function testExistsByName()
	{
		$aspName = 'a8';
		$exists = $this->repository->existsByName($aspName);
		$this->assertTrue($exists);

		$aspName = 'afb';
		$exists = $this->repository->existsByName($aspName);
		$this->assertTrue($exists);

		$aspName = 'dmm';
		$exists = $this->repository->existsByName($aspName);
		$this->assertFalse($exists);

		$aspName = 'felmat';
		$exists = $this->repository->existsByName($aspName);
		$this->assertTrue($exists);

		$aspName = 'not_exists';
		$exists = $this->repository->existsByName($aspName);
		$this->assertFalse($exists);
	}

	public function testGetAllAsps()
	{
		$expected = [
			new Asp(new AspId(1), 'a8', '&a8ejpredirect='),
			new Asp(new AspId(2), 'afb', ''),
			new Asp(new AspId(4), 'felmat', '')
		];
		$asps = $this->repository->getAll();
		$this->assertEquals($expected, $asps);
	}

	public function tearDown():void
	{
		parent::tearDown();
	}

}
