<?php

use AjaxSnippets\Api\Application\App\AppUpdateService;
use AjaxSnippets\Api\Domain\Models\App\IAppRepository;
use AjaxSnippets\Api\Domain\Models\App\AppId;
use AjaxSnippets\Api\Domain\Models\App\App;
use AjaxSnippets\Api\Application\App\AppUpdateCommand;
use AjaxSnippets\Api\Domain\Services\AppService;

class AppUpdateServiceTest extends WP_UnitTestCase
{
  private IAppRepository $appRepository;
  private AppUpdateService $appUpdateService;
  private AppService $appService;
  private App $app;

  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    parent::setUp();
    $this->appRepository = $diContainer->get(IAppRepository::class);
    $this->appService = new AppService($this->appRepository);
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "apps");
    $this->appUpdateService = new AppUpdateService($this->appRepository,$this->appService);

    // アプリの情報
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

  public function testUpdateCommand()
  {
    $req = new \WP_REST_Request();
    $req->set_param('id', 1);
    $req->set_param('img', 'img');
    $req->set_param('dev', 'dev');
    $req->set_param('iosLink', 'iosLink');
    $req->set_param('androidLink', 'androidLink');
    $req->set_param('webLink', 'webLink');
    $req->set_param('iosAffiLink', 'iosAffiLink');
    $req->set_param('androidAffiLink', 'androidAffiLink');
    $req->set_param('webAffiLink', 'webAffiLink');
    $req->set_param('article', 'article');
    $req->set_param('appOrder', 1);
    $req->set_param('appPrice', 1000);
    $command = new AppUpdateCommand($req);
    $this->assertEquals(1, $command->getId());
    $this->assertEquals('img', $command->getImg());
    $this->assertEquals('dev', $command->getDev());
    $this->assertEquals('iosLink', $command->getIosLink());
    $this->assertEquals('androidLink', $command->getAndroidLink());
    $this->assertEquals('webLink', $command->getWebLink());
    $this->assertEquals('iosAffiLink', $command->getIosAffiLink());
    $this->assertEquals('androidAffiLink', $command->getAndroidAffiLink());
    $this->assertEquals('webAffiLink', $command->getWebAffiLink());
    $this->assertEquals('article', $command->getArticle());
    $this->assertEquals(1, $command->getAppOrder());
    $this->assertEquals(1000, $command->getAppPrice());
  }

  public function testUpdate()
  {
    $this->appRepository->save($this->app);

    // アプリの情報
    $req = new \WP_REST_Request();
    $req->set_param('id', 1);
    $req->set_param('img', 'change-image');
    $req->set_param('dev', 'change-dev');
    $req->set_param('iosLink', 'iosLink');
    $req->set_param('androidLink', 'androidLink');
    $req->set_param('webLink', 'webLink');
    $req->set_param('iosAffiLink', 'iosAffiLink');
    $req->set_param('androidAffiLink', 'androidAffiLink');
    $req->set_param('webAffiLink', 'webAffiLink');
    $req->set_param('article', 'article');
    $req->set_param('appOrder', 2);
    $req->set_param('appPrice', 1000);
    $command = new AppUpdateCommand($req);
    $appId = $this->appUpdateService->handle($command);
    $this->assertEquals(new AppId(1), $appId);

    // きちんと更新されたか確認
    $res = $this->appRepository->findById(new AppId(1));
    $this->assertEquals('change-image', $res->getImage());
    $this->assertEquals('change-dev', $res->getDeveloper());
    $this->assertEquals(2, $res->getAppOrder());
  }
}