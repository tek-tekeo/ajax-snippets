<?php

use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Application\AdDetail\AdDetailCreateCommand;
use AjaxSnippets\Api\Application\AdDetail\AdDetailCreateService;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailRepository;
use AjaxSnippets\Api\Domain\Models\TagLink\ITagLinkRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailChartRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailInfoRepository;

class AdDetailCreateServiceTest extends WP_UnitTestCase
{
  private IAdDetailRepository $adDetailRepository;
  private AdDetailCreateService $adDetailCreateService;
  private IAdDetailChartRepository $adDetailChartRepository;
  private IAdDetailInfoRepository $adDetailInfoRepository;
  private ITagLinkRepository  $tagLinkRepository;
  private \WP_REST_Request $req;

  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    parent::setUp();
    $this->adDetailRepository = $diContainer->get(IAdDetailRepository::class);
    $this->tagLinkRepository = $diContainer->get(ITagLinkRepository::class);
    $this->adDetailChartRepository = $diContainer->get(IAdDetailChartRepository::class);
    $this->adDetailInfoRepository = $diContainer->get(IAdDetailInfoRepository::class);
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ad_details");
    $wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "tag_link");
    $wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ad_details_chart");
    $wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ad_details_info");
    $this->adDetailCreateService = new AdDetailCreateService(
      $this->adDetailRepository,
      $this->adDetailChartRepository,
      $this->adDetailInfoRepository,
      $this->tagLinkRepository
    );

    $this->req = new \WP_REST_Request();
    // 広告の情報
    $this->req->set_param('itemId', 0);
    $this->req->set_param('adId', 1);
    $this->req->set_param('itemName', 'itemName');
    $this->req->set_param('officialItemLink', 'officialItemLink');
    $this->req->set_param('affiItemLink', 'affiItemLink');
    $this->req->set_param('detailImg', 'detailImg');
    $this->req->set_param('amazonAsin', 'amazonAsin');
    $this->req->set_param('rakutenId', 'rakutenId');
    $this->req->set_param('rchart', [
      ['factor' => 'おすすめ度', 'value' => 4.4],
      ['factor' => 'ダメダメ度', 'value' => 1.1]
    ]);
    $this->req->set_param('info', [
      ['factor' => 'URL', 'value' => 'https://www.example.com'],
      ['factor' => '販売元', 'value' => 'まるまる商会']
    ]);
    $this->req->set_param('review', 'review');
    $this->req->set_param('isShowUrl', 1);
    $this->req->set_param('sameParent', 1);
    $this->req->set_param('tagIds', [1,2]);
  }

  public function testCommand()
  {
    $cmd = new AdDetailCreateCommand($this->req);
    $this->assertEquals(0, $cmd->getItemId());
    $this->assertEquals(1, $cmd->getAdId());
    $this->assertEquals('itemName', $cmd->getItemName());
    $this->assertEquals('officialItemLink', $cmd->getOfficialItemLink());
    $this->assertEquals('affiItemLink', $cmd->getAffiItemLink());
    $this->assertEquals('detailImg', $cmd->getDetailImg());
    $this->assertEquals('amazonAsin', $cmd->getAmazonAsin());
    $this->assertEquals('rakutenId', $cmd->getRakutenId());
    $this->assertEquals([
      ['factor' => 'おすすめ度', 'value' => 4.4],
      ['factor' => 'ダメダメ度', 'value' => 1.1]
    ], $cmd->getRates());
    $this->assertEquals([
      ['factor' => 'URL', 'value' => 'https://www.example.com'],
      ['factor' => '販売元', 'value' => 'まるまる商会']
    ], $cmd->getInfos());
    $this->assertEquals('review', $cmd->getReview());
    $this->assertEquals(1, $cmd->getIsShowUrl());
    $this->assertEquals(1, $cmd->getSameParent());
    $this->assertEquals([1,2], $cmd->getTagIds());
    
  }

  public function test_create()
  {
    $cmd = new AdDetailCreateCommand($this->req);
    // 新規登録されたら登録IDが返る
    $adDetailId = $this->adDetailCreateService->handle($cmd);
    $this->assertEquals(new AdDetailId(1), $adDetailId);

    $res = $this->tagLinkRepository->findByAdDetailId(new AdDetailId(1));
    $this->assertEquals(2, count($res));

    $res = $this->adDetailChartRepository->findByAdDetailId(new AdDetailId(1));
    $this->assertEquals(2, count($res));

    $res = $this->adDetailInfoRepository->findByAdDetailId(new AdDetailId(1));
    $this->assertEquals(2, count($res));

    // 同じadDetail名で登録されたら登録失敗となる
    // $this->expectException(\Exception::class);
    // $this->expectExceptionMessage('adDetail alreadDetaily exists');
    // $this->expectExceptionCode(500);
    // $adDetailId = $this->adDetailCreateService->handle($cmd);
  }
}
