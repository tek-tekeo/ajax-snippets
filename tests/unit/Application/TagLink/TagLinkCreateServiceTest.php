<?php

use AjaxSnippets\Api\Application\TagLink\TagLinkCreateService;
use AjaxSnippets\Api\Domain\Models\TagLink\ITagLinkRepository;
use AjaxSnippets\Api\Domain\Models\TagLink\TagLinkId;
use AjaxSnippets\Api\Application\TagLink\TagLinkCreateCommand;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailChartRepository;

class TagLinkCreateServiceTest extends WP_UnitTestCase
{
  private ITagLinkRepository $tagLinkRepository;
  private TagLinkCreateService $tagLinkCreateService;
  private IAdDetailChartRepository $adDetailChartRepository;

  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    parent::setUp();
    $this->tagLinkRepository = $diContainer->get(ITagLinkRepository::class);
    $this->adDetailChartRepository = $diContainer->get(IAdDetailChartRepository::class);
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "tag_link");
    $wpdb->insert(PLUGIN_DB_PREFIX . 'tag_link', ['id' => 1, 'ad_detail_id' => 1, 'tag_id' => 1]);
    $wpdb->insert(PLUGIN_DB_PREFIX . 'tag_link', ['id' => 2, 'ad_detail_id' => 2, 'tag_id' => 1]);

		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ad_details_chart");
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ad_details");
    $this->tagLinkCreateService = new TagLinkCreateService($this->tagLinkRepository, $this->adDetailChartRepository);
    $wpdb->insert(PLUGIN_DB_PREFIX . 'ad_details_chart', ['id' => 1, 'ad_detail_id' => 1, 'factor' => 'factor1-1', 'rate' => 1, 'sort_order' => 0]);
    $wpdb->insert(PLUGIN_DB_PREFIX . 'ad_details_chart', ['id' => 2, 'ad_detail_id' => 1, 'factor' => 'factor1-2', 'rate' => 3, 'sort_order' => 0]);
    $wpdb->insert(PLUGIN_DB_PREFIX . 'ad_details_chart', ['id' => 3, 'ad_detail_id' => 1, 'factor' => 'factor1-3', 'rate' => 5, 'sort_order' => 0]);
    $wpdb->insert(PLUGIN_DB_PREFIX . 'ad_details_chart', ['id' => 4, 'ad_detail_id' => 2, 'factor' => 'factor2-1', 'rate' => 2, 'sort_order' => 0]);
    $wpdb->insert(PLUGIN_DB_PREFIX . 'ad_details_chart', ['id' => 5, 'ad_detail_id' => 2, 'factor' => 'factor2-2', 'rate' => 4, 'sort_order' => 0]);
    $wpdb->insert(PLUGIN_DB_PREFIX . 'ad_details_chart', ['id' => 6, 'ad_detail_id' => 2, 'factor' => 'factor2-3', 'rate' => 5, 'sort_order' => 0]);
  }

  public function testCreate()
  {
    $request = new \WP_REST_Request();
    $request->set_param('tagId', 1);
    $request->set_param('adDetailId', 1);

    $cmd = new TagLinkCreateCommand($request);
    // 新規登録されたら登録IDが返る
    $tagLinkId = $this->tagLinkCreateService->handle($cmd);
    $this->assertEquals(new TagLinkId(3), $tagLinkId);

    // 同じタグ名で登録されたら登録失敗となる
    // $this->expectException(\Exception::class);
    // $this->expectExceptionMessage('tagLink already exists');
    // $this->expectExceptionCode(500);
    // $tagLinkId = $this->tagLinkCreateService->handle($command);
  }

}