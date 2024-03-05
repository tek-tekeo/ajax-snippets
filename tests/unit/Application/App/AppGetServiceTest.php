<?php

use AjaxSnippets\Api\Application\App\AppGetService;
use AjaxSnippets\Api\Application\DTO\AppData;
use AjaxSnippets\Api\Domain\Models\App\IAppRepository;
use AjaxSnippets\Api\Domain\Models\App\AppId;
use AjaxSnippets\Api\Domain\Models\App\App;
use AjaxSnippets\Api\Application\App\AppGetCommand;

class AppApplicationGetServiceTest extends WP_UnitTestCase
{
  private IAppRepository $appRepository;
  private AppGetService $appGetService;
  private App $app;

  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    parent::setUp();
    $this->appRepository = $diContainer->get(IAppRepository::class);
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "apps");
    $this->appGetService = new AppGetService($this->appRepository);
    $this->app = new App(
      new AppId(),
      'img',
      'dev',
      'iosLink',
      'androidLink',
      'webLink',
      'iosAffiLink',
      'androidAffiLink',
      'webAffiLink',
      'article',
      1,
      100
    );
  }

  public function testFindAppById()
  {
    $appId = new AppId(1);
    $this->appRepository->save($this->app);

    $request = new \WP_REST_Request();
    $request->set_param('id', $appId->getId());
    $command = new AppGetCommand($request);

    $actualAppData = $this->appGetService->handle($command);

    $expected = new AppData(
      new App(
        new AppId(1),
        'img',
        'dev',
        'iosLink',
        'androidLink',
        'webLink',
        'iosAffiLink',
        'androidAffiLink',
        'webAffiLink',
        'article',
        1,
        100
      )
    );
    $this->assertEquals($expected, $actualAppData);
  }

}