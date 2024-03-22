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
  }

  public function testUpdateCommand()
  {
    $req = new \WP_REST_Request();
    $req->set_param('id', 1);
    $req->set_param('img', 'img');
    $req->set_param('name', 'name');
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
    $this->assertEquals('name', $command->getName());
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
    $this->appRepository->save(new App(
      new AppId(2),
      'name2',
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
    ));

    // アプリの情報
    $req = new \WP_REST_Request();
    $req->set_param('id', 1);
    $req->set_param('name', 'change-name');
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
    $this->assertEquals('change-name', $res->getName());
    $this->assertEquals('change-image', $res->getImage());
    $this->assertEquals('change-dev', $res->getDeveloper());
    $this->assertEquals(2, $res->getAppOrder());
    
    // 重複した名前がある場合は更新できない
    $req = new \WP_REST_Request();
    $req->set_param('id', 2);
    $req->set_param('name', 'change-name');
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
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('app name alreappy exists');
    $this->expectExceptionCode(500);
    $appId = $this->appUpdateService->handle($command);

    // 重複した名前でも同じIDなら更新する
    $req = new \WP_REST_Request();
    $req->set_param('id', 1);
    $req->set_param('name', 'change-name');
    $req->set_param('img', 'change');
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
    // きちんと更新されたか確認
    $res = $this->appRepository->findById(new AppId(1));
    $this->assertEquals('change', $res->getImage());
  }
}