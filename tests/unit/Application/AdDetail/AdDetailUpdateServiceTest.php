<?php

use AjaxSnippets\Api\Application\AdDetail\AdDetailUpdateService;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailChartRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailInfoRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailReviewRepository;
use AjaxSnippets\Api\Domain\Models\Ad\IAdRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailChart;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailInfo;
use AjaxSnippets\Api\Application\AdDetail\AdDetailUpdateCommand;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Domain\Models\TagLink\ITagLinkRepository;
use AjaxSnippets\Api\Domain\Models\TagLink\TagLink;
use AjaxSnippets\Api\Domain\Models\TagLink\TagLinkId;
use AjaxSnippets\Api\Domain\Models\Tag\TagId;

class AdDetailUpdateServiceTest extends WP_UnitTestCase
{
  private IAdRepository $adRepository;
  private IAdDetailRepository $adDetailRepository;
  private IAdDetailChartRepository $adDetailChartRepository;
  private IAdDetailInfoRepository $adDetailInfoRepository;
  private IAdDetailReviewRepository $adDetailReviewRepository;
  private ITagLinkRepository $tagLinkRepository;
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
    $this->adDetailChartRepository = $diContainer->get(IAdDetailChartRepository::class);
    $this->adDetailInfoRepository = $diContainer->get(IAdDetailInfoRepository::class);
    $this->adDetailReviewRepository = $diContainer->get(IAdDetailReviewRepository::class);
    $this->tagLinkRepository = $diContainer->get(ITagLinkRepository::class);
    $wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ad_details");
    $wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ad_details_chart");
    $wpdb->insert(PLUGIN_DB_PREFIX . 'ad_details_chart', [
      'id' => 1,
      'ad_detail_id' => 3,
      'factor' => 'おすすめ度',
      'rate' => 4.4,
      'sort_order' => 2,
    ]);
    $wpdb->insert(PLUGIN_DB_PREFIX . 'ad_details_chart', [
      'id' => 2,
      'ad_detail_id' => 3,
      'factor' => 'ダメダメ度',
      'rate' => 1.1,
      'sort_order' => 1,
    ]);
    $wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ad_details_info");
    $wpdb->insert(PLUGIN_DB_PREFIX . 'ad_details_info', [
      'id' => 1,
      'ad_detail_id' => 3,
      'title' => 'URL',
      'content' => 'https://www.example.com',
      'sort_order' => 2,
    ]);
    $wpdb->insert(PLUGIN_DB_PREFIX . 'ad_details_info', [
      'id' => 2,
      'ad_detail_id' => 3,
      'title' => '販売元',
      'content' => 'まるまる商会',
      'sort_order' => 1,
    ]);
    $wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "tag_link");
    $this->adDetailUpdateService = new AdDetailUpdateService(
      $this->adRepository,
      $this->adDetailRepository,
      $this->adDetailChartRepository,
      $this->adDetailInfoRepository,
      $this->adDetailReviewRepository,
      $this->tagLinkRepository
    );

    $this->adDetail = new AdDetail(
      new AdDetailId(),
      new AdId(1),
      'itemName',
      'officialItemLink',
      'affiItemLink',
      'detailImg',
      'amazonAsin',
      'rakutenId',
      'rakutenAffiliateUrl',
      'review',
      1,
      1
    );

    $this->adDetailRepository->save($this->adDetail);
    $this->adDetailRepository->save($this->adDetail);
    $this->adDetailRepository->save($this->adDetail);
    $this->adDetailRepository->save($this->adDetail);

    $this->tagLinkRepository->save(
      new TagLink(
        new TagLinkId(),
        new AdDetailId(1),
        new TagId(1)
      )
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
    $this->req->set_param('rchart', [
      ['factor' => 'おすすめ度', 'value' => 4.3, 'sortOrder' => 2],
      ['factor' => 'ダメダメ度', 'value' => 1, 'sortOrder' => 1]
    ]);
    $this->req->set_param('info', [
      ['factor' => 'URL', 'value' => 'https://www.sample.com', 'sortOrder' => 2],
      ['factor' => '販売元', 'value' => 'まるまる商会', 'sortOrder' => 1]
    ]);
  }

  public function testUpdateHandle()
  {
    $request = new \WP_REST_Request();
    $request->set_param('id', 3);
    $request->set_param('itemName', 'adName100');
    $request->set_param('detailImg', 'detailImg');
    $request->set_param('amazonAsin', 'asin');
    $request->set_param('rakutenId', '');
    $request->set_param('rakutenAffiliateUrl', '');
    $request->set_param('review', 'review');
    $request->set_param('tagIds', [2, 3]);
    $request->set_param('rchart', [
      ['factor' => 'はちゃめちゃ度', 'value' => 2.3, 'sortOrder' => 2],
      ['factor' => 'オラオラ度', 'value' => 4.1, 'sortOrder' => 1]
    ]);
    $request->set_param('info', [
      ['factor' => 'URL', 'value' => 'https://www.sample.com', 'sortOrder' => 2],
      ['factor' => '販売元', 'value' => 'ばつばつ商会', 'sortOrder' => 1]
    ]);
    $command = new AdDetailUpdateCommand($request);
    $adDetailId = $this->adDetailUpdateService->handle($command);
    $this->assertEquals(3, $adDetailId);

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
      '',
      '',
      'review',
      0,
      0
    );
    $this->assertEquals($expected, $res);

    // タグリンクもきちんと更新されたか確認
    $res = $this->tagLinkRepository->findByAdDetailId(new AdDetailId(3));
    $expected = [
      new TagLink(new TagLinkId(2), new AdDetailId(3), new TagId(2)),
      new TagLink(new TagLinkId(3), new AdDetailId(3), new TagId(3)),
    ];
    $this->assertEquals($expected, $res);

    $res = $this->adDetailChartRepository->findByAdDetailId(new AdDetailId(3));
    $expected = [
      new AdDetailChart(4, new AdDetailId(3), 'オラオラ度', 4.1, 1),
      new AdDetailChart(3, new AdDetailId(3), 'はちゃめちゃ度', 2.3, 2),
    ];
    $this->assertEquals($expected, $res);

    $res = $this->adDetailInfoRepository->findByAdDetailId(new AdDetailId(3));

    $expected = [
      new AdDetailInfo(4, new AdDetailId(3), '販売元', 'ばつばつ商会', 1),
      new AdDetailInfo(3, new AdDetailId(3), 'URL', 'https://www.sample.com', 2),
    ];
    $this->assertEquals($expected, $res);
  }
}
