<?php

use AjaxSnippets\Api\Application\Ad\AdUpdateService;
use AjaxSnippets\Api\Domain\Models\Ad\IAdRepository;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Domain\Models\Ad\Ad;
use AjaxSnippets\Api\Domain\Models\App\AppId;
use AjaxSnippets\Api\Application\Ad\AdUpdateCommand;

class AdUpdateServiceTest extends WP_UnitTestCase
{
  private IAdRepository $adRepository;
  private AdUpdateService $adUpdateService;
  private \WP_REST_Request $req;
  private Ad $ad;
  
  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    parent::setUp();
    $this->adRepository = $diContainer->get(IAdRepository::class);
    $wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ads");
    $this->adUpdateService = new AdUpdateService($this->adRepository);

    $adId = new AdId();
    $this->ad = new Ad(
      $adId,
      'Main Ad Name',
      'anken-link',
      'https://www.anken.com',
      'https://www.item-link.com',
      'a8',
      'banner-image.jpg',
      'image-tag-url',
      'item-image-tag-url',
      300,
      250,
      new AppId()
    );

    // 広告の情報
    $this->req = new \WP_REST_Request();
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
  }

  public function testUpdate()
  {
    $this->adRepository->save($this->ad);
    $this->adRepository->save($this->ad);
    $this->adRepository->save($this->ad);
    $this->adRepository->save($this->ad);

    $request = new \WP_REST_Request();
    $request->set_param('id', 3);
    $request->set_param('name', 'adName100');
    $request->set_param('affiImgHeight', 100);
    $command = new AdUpdateCommand($request);
    $adId = $this->adUpdateService->handle($command);
    $this->assertEquals(new AdId(3), $adId);

    // きちんと更新されたか確認
    $res = $this->adRepository->findById(new AdId(3));
    $expected = new Ad(
      new AdId(3),
      'adName100',
      'anken-link',
      'https://www.anken.com',
      'https://www.item-link.com',
      'a8',
      'banner-image.jpg',
      'image-tag-url',
      'item-image-tag-url',
      300,
      100,
      new AppId(0)
    );
    $this->assertEquals($expected, $res);
  }
}