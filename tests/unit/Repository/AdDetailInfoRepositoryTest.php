<?php
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailInfo;
use AjaxSnippets\Api\Infrastructure\Repository\AdDetailInfoRepository;

final class AdDetailInfoRepositoryTest extends WP_UnitTestCase
{
  private $repository;

	public function setUp():void
	{
		parent::setUp();
		$this->resetDatabase();
		$this->repository = new AdDetailInfoRepository();
	}

	protected function resetDatabase()
	{
		global $wpdb;
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ad_details_info");
    $wpdb->insert(PLUGIN_DB_PREFIX . 'ad_details_info', [
      'id' => 0,
      'ad_detail_id' => 1,
      'title' => 'URL',
      'content' => 'https://www.example.com',
      'sort_order' => 2,
    ]);
    $wpdb->insert(PLUGIN_DB_PREFIX . 'ad_details_info', [
      'id' => 0,
      'ad_detail_id' => 1,
      'title' => '販売元',
      'content' => 'まるまる商会',
      'sort_order' => 1,
    ]);
	}

  public function testSaveAdDetailInfo()
  {
    $adDetailId = new AdDetailId(1);
    $obj = new AdDetailInfo(
      0,
      $adDetailId,
      '技術',
      '最新式',
      0,
    );

    $insertId = $this->repository->save($obj);
    $this->assertEquals(3, $insertId);
  }

  public function testFindByAdDetailId()
  {
    $adDetailId = new AdDetailId(1);
    $res = $this->repository->findByAdDetailId($adDetailId);

    $this->assertInstanceOf(AdDetailInfo::class, $res[0]);
    $this->assertEquals(
      [
        new AdDetailInfo(2, new AdDetailId(1), '販売元', 'まるまる商会', 1),
        new AdDetailInfo(1, new AdDetailId(1), 'URL', 'https://www.example.com', 2)
      ],
      $res
    );
  }

  public function testDeleteByAdDetailId()
  {
    $adDetailId = new AdDetailId(1);
    $result = $this->repository->deleteByAdDetailId($adDetailId);
    $this->assertTrue($result);
    $adDetailInfos = $this->repository->findByAdDetailId($adDetailId);
    $this->assertCount(0, $adDetailInfos);
  }
}