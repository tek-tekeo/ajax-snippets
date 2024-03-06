<?php

use AjaxSnippets\Api\Application\Ad\AdCreateService;
use AjaxSnippets\Api\Domain\Models\Ad\IAdRepository;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Application\Ad\IAdCreateService;
use AjaxSnippets\Api\Application\Ad\AdCreateCommand;
use AjaxSnippets\Api\Domain\Models\App\AppId;
use AjaxSnippets\Api\Domain\Services\AdService;

class AdCreateServiceTest extends WP_UnitTestCase
{
  private IAdRepository $adRepository;
  private AdCreateService $adCreateService;
  private \WP_REST_Request $req;

  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    parent::setUp();
    $this->adRepository = $diContainer->get(IAdRepository::class);
    // $this->adService = new AdService($this->adRepository);
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ads");
    $this->adCreateService = new AdCreateService($this->adRepository);

    $this->req = new \WP_REST_Request();
    // 広告の情報
    $this->req->set_param('id', 'id');
    $this->req->set_param('name', 'name');
    $this->req->set_param('anken', 'anken');
    $this->req->set_param('affiLink', 'affiLink');
    $this->req->set_param('sLink', 'sLink');
    $this->req->set_param('aspName', 'aspName');
    $this->req->set_param('affiImg', 'affiImg');
    $this->req->set_param('imgTag', 'imgTag');
    $this->req->set_param('sImgTag', 'sImgTag');
    $this->req->set_param('affiImgWidth', 300);
    $this->req->set_param('affiImgHeight', 250);
    
    // 広告の商品リンクに使用するURL
    $this->req->set_param('homeUrl', 'homeUrl');
  }

  public function testCommand()
  {
    $cmd = new AdCreateCommand($this->req, new AppId(1));
    $this->assertEquals(['name', 'anken', 'affiLink', 'sLink',
      'aspName', 'affiImg', 'imgTag', 'sImgTag', 300, 250
    ], $cmd->getAdInfo());

    $this->assertEquals('homeUrl', $cmd->getHomeUrl());
  }

  public function test_create()
  {
    $cmd = new AdCreateCommand($this->req, new AppId(1));
    // 新規登録されたら登録IDが返る
    $adId = $this->adCreateService->handle($cmd);
    $this->assertEquals(new AdId(1), $adId);

    // 同じad名で登録されたら登録失敗となる
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('ad already exists');
    $this->expectExceptionCode(500);
    $adId = $this->adCreateService->handle($cmd);
  }
}
