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
	}

	protected function resetDatabase()
	{
		global $wpdb;
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "apps");
	}

	public function testSave()
	{
		$this->assertEquals(new AppId(1), $this->repository->save($this->app));
	}

	public function testFindById()
	{
		$this->repository->save($this->app);
		$this->repository->save($this->app);
		$this->repository->save($this->app);
		$this->repository->save($this->app);

		$expected = new App(
			new AppId(3),
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

	public function testDeleteById()
	{
		$this->repository->save($this->app);
		$this->repository->save($this->app);
		$this->repository->save($this->app);
		$this->repository->save($this->app);

		$this->assertTrue($this->repository->delete(new AppId(3)));
		$this->assertNull($this->repository->findById(new AppId(3)));
	}
	public function tearDown():void
	{
		parent::tearDown();
	}

}
