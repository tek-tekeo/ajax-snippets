<?php

use AjaxSnippets\Api\Domain\Models\App\App;
use AjaxSnippets\Api\Domain\Models\App\AppId;
use AjaxSnippets\Api\Infrastructure\Repository\AppRepository;
class AppRepositoryTest extends WP_UnitTestCase
{
	private $repository;
	private $app;

	public function setUp():void
	{
		parent::setUp();
		$this->resetDatabase();
		$this->repository = new AppRepository();
		$this->app = new App(
			new AppId(),
			'name',
			'img',
			'dev',
			'ios link',
			'android link',
			'web link',
			'ios affi link',
			'android affi link',
			'web affi link',
			'article',
			1,
			1
		);

		$this->repository->save($this->app);
		$this->repository->save($this->app);
		$this->repository->save($this->app);
		$this->repository->save($this->app);

	}

	protected function resetDatabase()
	{
		global $wpdb;
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "apps");
	}

	public function testSave()
	{
		$this->assertEquals(new AppId(5), $this->repository->save($this->app));
	}

	public function testGetAll(){
		$res = $this->repository->getAll();
		$cols = [
			'name',
			'img',
			'dev',
			'ios link',
			'android link',
			'web link',
			'ios affi link',
			'android affi link',
			'web affi link',
			'article',
			1,
			1
		];
		$expected = [
			new App(new AppId(1), ...$cols),
			new App(new AppId(2), ...$cols),
			new App(new AppId(3), ...$cols),
			new App(new AppId(4), ...$cols)
		];
		$this->assertEquals($expected, $res);
	}

	public function testFindById()
	{
		$expected = new App(
			new AppId(3),
			'name',
			'img',
			'dev',
			'ios link',
			'android link',
			'web link',
			'ios affi link',
			'android affi link',
			'web affi link',
			'article',
			1,
			1
		);

		$this->assertEquals($expected, $this->repository->findById(new AppId(3)));
	}

	public function testFindByName()
	{
		$expected = new App(
			new AppId(1),
			'name',
			'img5',
			'dev',
			'ios link',
			'android link',
			'web link',
			'ios affi link',
			'android affi link',
			'web affi link',
			'article',
			1,
			1
		);
		$this->repository->save($expected);
		$this->assertEquals($expected, $this->repository->findByName('name',new AppId(2)));
	
	}

	public function testDeleteById()
	{
		$this->assertTrue($this->repository->delete(new AppId(3)));
		$this->assertNull($this->repository->findById(new AppId(3)));
	}

	public function tearDown():void
	{
		parent::tearDown();
	}

}
