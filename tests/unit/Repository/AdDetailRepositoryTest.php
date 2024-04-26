<?php
use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Domain\Models\Ad\Ad;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;
use AjaxSnippets\Api\Infrastructure\Repository\AdDetailRepository;
use AjaxSnippets\Api\Domain\Models\App\AppId;

final class AdDetailRepositoryTest extends WP_UnitTestCase
{
  private $repository;

	public function setUp():void
	{
		parent::setUp();
		$this->resetDatabase();
		$this->repository = new AdDetailRepository();
	}

	protected function resetDatabase()
	{
		global $wpdb;
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ad_details");
	}

  public function testSaveAdDetail()
  {
    $adDetailId = new AdDetailId();
    $adId = new AdId();
    $adDetail = new AdDetail(
      $adDetailId,
      $adId,
      'item name',
      'official item link',
      'affi item link',
      'detail image',
      'amazon asin',
      'rakuten id',
      'review',
      1,
      1
    );

    $insertId = $this->repository->save($adDetail);
    $this->assertEquals(new AdDetailId(1), $insertId);
  }

  public function testFindById()
  {
    $adDetailId = new AdDetailId(1);

    $adId = new AdId();
    $adDetail = new AdDetail(
      $adDetailId,
      $adId,
      'item name',
      'official item link',
      'affi item link',
      'detail image',
      'amazon asin',
      'rakuten id',
      'review',
      1,
      1
    );

    $insertId = $this->repository->save($adDetail);
    $getAdDetail = $this->repository->findById($insertId);
    $this->assertInstanceOf(AdDetail::class, $getAdDetail);
    $this->assertEquals($adDetail, $getAdDetail);

    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('Ad Detail IDに該当するデータが存在しません。');
    $this->expectExceptionCode(500);
    $getAdDetail = $this->repository->findById(new AdDetailId(3));
  }

  public function testFindLatest()
  {
    $adDetailId = new AdDetailId(1);
    $adId = new AdId();
    $adDetail = new AdDetail(
      $adDetailId,
      $adId,
      'item name',
      'official item link',
      'affi item link',
      'detail image',
      'amazon asin',
      'rakuten id',
      'review',
      1,
      1
    );

    $this->repository->save($adDetail);
    $res = $this->repository->findLatest();
    $this->assertInstanceOf(AdDetail::class, $res);
    $this->assertEquals($adDetail, $res);
  }

  public function testFindByAdId()
  {
    $adId = new AdId(1);
    $adDetailId = new AdDetailId();
    $adDetail = new AdDetail(
      $adDetailId,
      $adId,
      'item name',
      'official item link',
      'affi item link',
      'detail image',
      'amazon asin',
      'rakuten id',
      'review',
      1,
      1
    );

    $this->repository->save($adDetail);
    $this->repository->save($adDetail);
    $adDetails = $this->repository->findByAdId(new AdId(1));
    $expectedCount = 2;
    $this->assertCount($expectedCount, $adDetails);
    $this->assertEquals([
      new AdDetail(
        new AdDetailId(1),
        new AdId(1),
        'item name',
        'official item link',
        'affi item link',
        'detail image',
        'amazon asin',
        'rakuten id',
        'review',
        1,
        1
      ),
      new AdDetail(
        new AdDetailId(2),
        new AdId(1),
        'item name',
        'official item link',
        'affi item link',
        'detail image',
        'amazon asin',
        'rakuten id',
        'review',
        1,
        1
      ),
    ], $adDetails);
  }

  public function testDeleteByAdId()
  {
        $adId = new AdId(1);
    $adDetailId = new AdDetailId();
    $adDetail = new AdDetail(
      $adDetailId,
      $adId,
      'item name',
      'official item link',
      'affi item link',
      'detail image',
      'amazon asin',
      'rakuten id',
      'review',
      1,
      1
    );

    $this->repository->save($adDetail);
    $this->repository->save($adDetail);
    $result = $this->repository->deleteByAdId(new AdId(1));
    $this->assertTrue($result);
    $adDetails = $this->repository->findByAdId(new AdId(1));
    $this->assertCount(0, $adDetails);
  }
}