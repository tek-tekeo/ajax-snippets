<?php

use AjaxSnippets\Api\Application\Ad\AdCreateService;
use AjaxSnippets\Api\Domain\Models\Ad\IAdRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailRepository;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Application\Ad\IAdCreateService;
use AjaxSnippets\Api\Application\Ad\AdCreateCommand;
use AjaxSnippets\Api\Domain\Models\App\AppId;
use AjaxSnippets\Api\Domain\Models\Asp\AspId;
use AjaxSnippets\Api\Domain\Services\AdService;

class AdCreateServiceTest extends WP_UnitTestCase
{
  private IAdRepository $adRepository;
  private AdCreateService $adCreateService;
  private IAdDetailRepository $adDetailRepository;
  private \WP_REST_Request $req;

  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    parent::setUp();
    $this->adRepository = $diContainer->get(IAdRepository::class);
    $this->adDetailRepository = $diContainer->get(IAdDetailRepository::class);
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ads");
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ad_details");
    $this->adCreateService = new AdCreateService($this->adRepository, $this->adDetailRepository);

    $this->req = new \WP_REST_Request();
    // 広告の情報
    $this->req->set_param('id', 'id');
    $this->req->set_param('name', 'name');
    $this->req->set_param('anken', 'anken');
    $this->req->set_param('affiLink', 'affiLink');
    $this->req->set_param('sLink', 'sLink');
    $this->req->set_param('aspId', 1);
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
    $cmd = new AdCreateCommand($this->req);
    $this->assertEquals(['name', 'anken', 'affiLink', 'sLink',
      1, 'affiImg', 'imgTag', 'sImgTag', 300, 250
    ], $cmd->getAdInfo());

    $this->assertEquals('homeUrl', $cmd->getHomeUrl());
  }

  public function testCURD()
  {
    $cmd = new AdCreateCommand($this->req);
    // 新規登録されたら登録IDが返る
    $adId = $this->adCreateService->handle($cmd);
    $this->assertEquals(new AdId(1), $adId);

    // 登録された広告を取得する
    $ad = $this->adRepository->findById(new AdId(1));
    $this->assertEquals('name', $ad->getName());
    $this->assertEquals('anken', $ad->getAnken());
    $this->assertEquals('affiLink', $ad->getAffiLink());
    $this->assertEquals('sLink', $ad->getSLink());
    $this->assertEquals(new AspId(1), $ad->getAspId());
    $this->assertEquals('affiImg', $ad->getAffiImg());
    $this->assertEquals('imgTag', $ad->getImgTag());
    $this->assertEquals('sImgTag', $ad->getSImgTag());
    $this->assertEquals(300, $ad->getAffiImgWidth());
    $this->assertEquals(250, $ad->getAffiImgHeight());
    $this->assertEquals(new AppId(0), $ad->getAppId());

    // 登録された広告の商品リンクに使用するURLを取得する
    $adDetails = $this->adDetailRepository->findByAdId(new AdId(1));
    $this->assertEquals(new AdDetail(
      new AdDetailId(1),
      new AdId(1),
      '',
      '',
      '',
      '',
      '',
      '',
      '',
      0,
      1
    
    ), $adDetails[0]);

    // 同じad名で登録されたら登録失敗となる
    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('ad already exists');
    $this->expectExceptionCode(500);
    $adId = $this->adCreateService->handle($cmd);
  }
}
