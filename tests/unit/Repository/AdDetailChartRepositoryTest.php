<?php
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailChart;
use AjaxSnippets\Api\Infrastructure\Repository\AdDetailChartRepository;

final class AdDetailChartRepositoryTest extends WP_UnitTestCase
{
  private $repository;

	public function setUp():void
	{
		parent::setUp();
		$this->resetDatabase();
		$this->repository = new AdDetailChartRepository();
	}

	protected function resetDatabase()
	{
		global $wpdb;
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ad_details_chart");
    $wpdb->insert(PLUGIN_DB_PREFIX . 'ad_details_chart', [
      'id' => 0,
      'ad_detail_id' => 1,
      'factor' => 'おすすめ度',
      'rate' => 4.4,
      'sort_order' => 2,
    ]);
    $wpdb->insert(PLUGIN_DB_PREFIX . 'ad_details_chart', [
      'id' => 0,
      'ad_detail_id' => 1,
      'factor' => 'ダメダメ度',
      'rate' => 1.1,
      'sort_order' => 1,
    ]);
	}

  public function testSaveAdDetailChart()
  {
    $adDetailId = new AdDetailId();
    $obj = new AdDetailChart(
      0,
      $adDetailId,
      'おすすめ度',
      4.4,
      1,
    );

    $insertId = $this->repository->save($obj);
    $this->assertEquals(3, $insertId);
  }

  public function testFindByAdDetailId()
  {
    $adDetailId = new AdDetailId(1);
    $res = $this->repository->findByAdDetailId($adDetailId);

    $this->assertInstanceOf(AdDetailChart::class, $res[0]);
    $this->assertEquals(
      [
        new AdDetailChart(2, new AdDetailId(1), 'ダメダメ度', 1.1, 1),
        new AdDetailChart(1, new AdDetailId(1), 'おすすめ度', 4.4, 2)
      ],
      $res
    );
  }

  public function testDeleteByAdDetailId()
  {
    $adDetailId = new AdDetailId(1);
    $result = $this->repository->deleteByAdDetailId($adDetailId);
    $this->assertTrue($result);
    $adDetailCharts = $this->repository->findByAdDetailId($adDetailId);
    $this->assertCount(0, $adDetailCharts);
  }
}