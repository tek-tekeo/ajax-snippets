<?php

use AjaxSnippets\Api\Application\App\AppDeleteService;
use AjaxSnippets\Api\Domain\Models\App\IAppRepository;
use AjaxSnippets\Api\Domain\Models\App\AppId;
use AjaxSnippets\Api\Domain\Models\App\App;
use AjaxSnippets\Api\Application\App\AppDeleteCommand;

class AppApplicationDeleteServiceTest extends WP_UnitTestCase
{
  private IAppRepository $appRepository;
  private AppDeleteService $appDeleteService;
  private App $app;

  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    parent::setUp();
    $this->appRepository = $diContainer->get(IAppRepository::class);
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "apps");
    $this->appDeleteService = new AppDeleteService($this->appRepository);
    $this->app = new App(new AppId(),
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

  public function testdelete()
  {
    $appId = $this->appRepository->save($this->app);
    
    $request = new \WP_REST_Request();
    $request->set_param('id', $appId->getId());
    $command = new AppDeleteCommand($request);
    $result = $this->appDeleteService->handle($command);
    $this->assertTrue($result);
  }
}