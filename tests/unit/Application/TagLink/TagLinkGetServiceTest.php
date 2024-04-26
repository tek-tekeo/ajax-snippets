<?php


use AjaxSnippets\Api\Domain\Models\TagLink\ITagLinkRepository;
use AjaxSnippets\Api\Application\TagLink\TagLinkGetService;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailChartRepository;


class TagLinkGetServiceTest extends WP_UnitTestCase
{
  private ITagLinkRepository $tagLinkRepository;
  private IAdDetailRepository $adDetailRepository;
  private IAdDetailChartRepository $adDetailChartRepository;
  private TagLinkGetService $tagLinkGetService;

  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    parent::setUp();
    $this->tagLinkRepository = $diContainer->get(ITagLinkRepository::class);
    $this->adDetailRepository = $diContainer->get(IAdDetailRepository::class);
    $this->adDetailChartRepository = $diContainer->get(IAdDetailChartRepository::class);
    $this->tagLinkGetService = new TagLinkGetService($this->tagLinkRepository, $this->adDetailRepository, $this->adDetailChartRepository);

    $this->tagLinkRepository = $diContainer->get(ITagLinkRepository::class);
    $this->adDetailChartRepository = $diContainer->get(IAdDetailChartRepository::class);
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "tag_link");
    $wpdb->insert(PLUGIN_DB_PREFIX . 'tag_link', ['id' => 1, 'ad_detail_id' => 1, 'tag_id' => 1]);
    $wpdb->insert(PLUGIN_DB_PREFIX . 'tag_link', ['id' => 2, 'ad_detail_id' => 2, 'tag_id' => 1]);

    $wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ad_details");
    $wpdb->insert(PLUGIN_DB_PREFIX . 'ad_details', [
      'id' => 1,
      'ad_id' => 1,
      'item_name' => 'item_name1',
      'official_item_link' => 'official_item_link',
      'affi_item_link' => 'affi_item_link',
      'detail_img' => 'detail_img',
      'amazon_asin' => 'amazon_asin',
      'rakuten_id' => 'rakuten_id',
      'review' => 'review',
      'is_show_url' => 1,
      'same_parent' => 0
      ]
    );
    $wpdb->insert(PLUGIN_DB_PREFIX . 'ad_details', [
      'id' => 2,
      'ad_id' => 1,
      'item_name' => 'item_name2',
      'official_item_link' => 'official_item_link',
      'affi_item_link' => 'affi_item_link',
      'detail_img' => 'detail_img',
      'amazon_asin' => 'amazon_asin',
      'rakuten_id' => 'rakuten_id',
      'review' => 'review',
      'is_show_url' => 1,
      'same_parent' => 0
      ]
    );
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ad_details_chart");
    $wpdb->insert(PLUGIN_DB_PREFIX . 'ad_details_chart', ['id' => 1, 'ad_detail_id' => 1, 'factor' => 'factor1-1', 'rate' => 1, 'sort_order' => 0]);
    $wpdb->insert(PLUGIN_DB_PREFIX . 'ad_details_chart', ['id' => 2, 'ad_detail_id' => 1, 'factor' => 'factor1-2', 'rate' => 3, 'sort_order' => 0]);
    $wpdb->insert(PLUGIN_DB_PREFIX . 'ad_details_chart', ['id' => 3, 'ad_detail_id' => 1, 'factor' => 'factor1-3', 'rate' => 5, 'sort_order' => 0]);
    $wpdb->insert(PLUGIN_DB_PREFIX . 'ad_details_chart', ['id' => 4, 'ad_detail_id' => 2, 'factor' => 'factor2-1', 'rate' => 2, 'sort_order' => 0]);
    $wpdb->insert(PLUGIN_DB_PREFIX . 'ad_details_chart', ['id' => 5, 'ad_detail_id' => 2, 'factor' => 'factor2-2', 'rate' => 4, 'sort_order' => 0]);
    $wpdb->insert(PLUGIN_DB_PREFIX . 'ad_details_chart', ['id' => 6, 'ad_detail_id' => 2, 'factor' => 'factor2-3', 'rate' => 5, 'sort_order' => 0]);
  }

  public function testTrue()
  {
    $this->assertTrue(true);
  }

  public function testHandle()
  {
    $this->assertTrue(true);
  }
  // public function testGetTag()
  // {
  //   $this->tagRepository->save(new Tag(new TagId(1), 'tagName1', 1));
  //   $this->tagRepository->save(new Tag(new TagId(2), 'tagName2', 2));

  //   $tagId = new TagId(1);
  //   $request = new \WP_REST_Request();
  //   $request->set_param('id', $tagId->getId());
  //   $command = new TagGetCommand($request);

  //   $actualTagData = $this->tagLinkGetService->handle($command);

  //   $expected = new TagData(new Tag(new TagId(1), 'tagName1', 1));
  //   $this->assertEquals($expected, $actualTagData);

  //   // 全てを取得する
  //   $actualTagData = $this->tagLinkGetService->getAll();
  //   $expected = [
  //     new TagData(new Tag(new TagId(1), 'tagName1', 1)),
  //     new TagData(new Tag(new TagId(2), 'tagName2', 2))
  //   ];
  //   $this->assertEquals($expected, $actualTagData);

  //   // 検索する ヒットする
  //   $request = new \WP_REST_Request();
  //   $request->set_param('name', 'tagName');
  //   $command = new TagSearchCommand($request);
  //   $actualTagData = $this->tagLinkGetService->search($command);
  //   $expected = [
  //     new TagData(new Tag(new TagId(1), 'tagName1', 1)),
  //     new TagData(new Tag(new TagId(2), 'tagName2', 2))
  //   ];
  //   $this->assertEquals($expected, $actualTagData);
    
  //   // ヒットしない
  //   $request = new \WP_REST_Request();
  //   $request->set_param('name', 'tagName3');
  //   $command = new TagSearchCommand($request);
  //   $actualTagData = $this->tagLinkGetService->search($command);
  //   $expected = [];
  //   $this->assertEquals($expected, $actualTagData);
    
  // }

  public function testCrateTagRanking()
  {
    $res = $this->tagLinkGetService->getTagRanking("1");
    $this->assertEquals([
      ['adDetailId' => 2, 'name' => 'item_name2', 'rate' => 3.667],
      ['adDetailId' => 1, 'name' => 'item_name1', 'rate' => 3.0],
    ], $res);
  }

}