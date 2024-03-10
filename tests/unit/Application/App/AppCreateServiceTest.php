<?php

use AjaxSnippets\Api\Application\App\AppCreateService;
use AjaxSnippets\Api\Domain\Models\App\IAppRepository;
use AjaxSnippets\Api\Domain\Models\App\AppId;
use AjaxSnippets\Api\Application\App\IAppCreateService;
use AjaxSnippets\Api\Application\App\AppCreateCommand;
use AjaxSnippets\Api\Domain\Services\AppService;

class AppCreateServiceTest extends WP_UnitTestCase
{
  private IAppRepository $appRepository;
  private AppCreateService $appCreateService;
  private \WP_REST_Request $req;

  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    parent::setUp();
    $this->appRepository = $diContainer->get(IAppRepository::class);
    // $this->appService = new AppService($this->appRepository);
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "apps");
    $this->appCreateService = new AppCreateService($this->appRepository);

    // アプリの情報
    $this->req = new \WP_REST_Request();
    $this->req->set_param('id', 1);
    $this->req->set_param('name', 'name');
    $this->req->set_param('img', 'img');
    $this->req->set_param('dev', 'dev');
    $this->req->set_param('iosLink', 'iosLink');
    $this->req->set_param('androidLink', 'androidLink');
    $this->req->set_param('webLink', 'webLink');
    $this->req->set_param('iosAffiLink', 'iosAffiLink');
    $this->req->set_param('androidAffiLink', 'androidAffiLink');
    $this->req->set_param('webAffiLink', 'webAffiLink');
    $this->req->set_param('article', 'article');
    $this->req->set_param('appOrder', 1);
    $this->req->set_param('appPrice', 1000);
  }

  public function testCommand()
  {
    $cmd = new AppCreateCommand($this->req);
    $this->assertEquals('name', $cmd->getName());
    $this->assertEquals('img', $cmd->getImg());
    $this->assertEquals('dev', $cmd->getDev());
    $this->assertEquals('iosLink', $cmd->getIosLink());
    $this->assertEquals('androidLink', $cmd->getAndroidLink());
    $this->assertEquals('webLink', $cmd->getWebLink());
    $this->assertEquals('iosAffiLink', $cmd->getIosAffiLink());
    $this->assertEquals('androidAffiLink', $cmd->getAndroidAffiLink());
    $this->assertEquals('webAffiLink', $cmd->getWebAffiLink());
    $this->assertEquals('article', $cmd->getArticle());
    $this->assertEquals(1, $cmd->getAppOrder());
    $this->assertEquals(1000, $cmd->getAppPrice());

  }

  public function testCreateApp()
  {
    $cmd = new AppCreateCommand($this->req);
    // 新規登録されたら登録IDが返る
    $appId = $this->appCreateService->handle($cmd);
    $this->assertEquals(new AppId(1), $appId);

    // 同じapp名で登録されたら登録失敗となる // 実装していない

    $req = new \WP_REST_Request();
    $req->set_param('id', 2);
    $req->set_param('name', 'name');
    $existNameCmd = new AppCreateCommand($req);
    $this->assertEquals(new AppId(1), $appId);
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('app alreappy exists');
    $this->expectExceptionCode(500);
    $appId = $this->appCreateService->handle($existNameCmd);
  }
}
