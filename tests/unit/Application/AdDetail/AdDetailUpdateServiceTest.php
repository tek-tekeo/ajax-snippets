<?php

use AjaxSnippets\Api\Application\AdDetail\AdDetailUpdateService;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailRepository;
use AjaxSnippets\Api\Domain\Models\Ad\IAdRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;
use AjaxSnippets\Api\Application\AdDetail\AdDetailUpdateCommand;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;

class AdDetailUpdateServiceTest extends WP_UnitTestCase
{
  private IAdRepository $adRepository;
  private IAdDetailRepository $adDetailRepository;
  private AdDetailUpdateService $adDetailUpdateService;
  private \WP_REST_Request $req;
  private AdDetail $adDetail;
  
  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    parent::setUp();
    $this->adRepository = $diContainer->get(IAdRepository::class);
    $this->adDetailRepository = $diContainer->get(IAdDetailRepository::class);
    $wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ad_details");
    $this->adDetailUpdateService = new AdDetailUpdateService($this->adRepository, $this->adDetailRepository);

    $adId = new AdDetailId();

    $this->adDetail = new AdDetail(
      new AdDetailId(),
      new AdId(1),
      'itemName',
      'officialItemLink',
      'affiItemLink',
      'detailImg',
      'amazonAsin',
      'rakutenId',
      '[]',
      '[]',
      'review',
      1,
      1
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

  public function testUpdateHandle()
  {
    $this->adDetailRepository->save($this->adDetail);
    $this->adDetailRepository->save($this->adDetail);
    $this->adDetailRepository->save($this->adDetail);
    $this->adDetailRepository->save($this->adDetail);

    $request = new \WP_REST_Request();
    $request->set_param('id', 3);
    $request->set_param('itemName', 'adName100');
    $request->set_param('amazonAsin', 'asin');
    $command = new AdDetailUpdateCommand($request);
    $adDetailId = $this->adDetailUpdateService->handle($command);
    $this->assertEquals(new AdDetailId(3), $adDetailId);

    // きちんと更新されたか確認
    $res = $this->adDetailRepository->findById(new AdDetailId(3));
    $expected = new AdDetail(
      new AdDetailId(3),
      new AdId(1),
      'adName100',
      'officialItemLink',
      'affiItemLink',
      'detailImg',
      'asin',
      'rakutenId',
      '[]',
      '[]',
      'review',
      1,
      1
    );
    $this->assertEquals($expected, $res);
  }
}