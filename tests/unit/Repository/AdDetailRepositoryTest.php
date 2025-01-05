<?php

use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Domain\Models\Ad\Ad;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;
use AjaxSnippets\Api\Infrastructure\Repository\AdDetailRepository;
use AjaxSnippets\Api\Infrastructure\Repository\AdRepository;
use AjaxSnippets\Api\Domain\Models\App\AppId;
use AjaxSnippets\Api\Domain\Models\Asp\AspId;

final class AdDetailRepositoryTest extends WP_UnitTestCase
{
  private $repository;
  private $adRepository;

  public function setUp(): void
  {
    parent::setUp();
    $this->resetDatabase();
    $this->repository = new AdDetailRepository();
    $this->adRepository = new AdRepository();
  }

  protected function resetDatabase()
  {
    global $wpdb;
    $wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ads");
    $wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ad_details");
  }

  public function testSaveAdDetail()
  {
    $adDetailId = new AdDetailId();
    $adId = new AdId(1);
    $adDetail = new AdDetail(
      $adDetailId,
      $adId,
      'item name',
      'official item link',
      'affi item link',
      'detail image',
      'amazon asin',
      'rakuten id',
      'rakuten affiliate url',
      'review',
      1,
      1,
      '2021-01-01 00:00:00',
      '2022-01-01 00:00:00',
      '2022-02-01 00:00:00',
    );

    $insertId = $this->repository->save($adDetail);
    $this->assertEquals(new AdDetailId(1), $insertId);

    $savedAdDetail = $this->repository->findById($insertId);
    $this->assertEquals(
      [
        'id' => 1,
        'ad_id' => 1,
        'item_name' => 'item name',
        'official_item_link' => 'official item link',
        'affi_item_link' => 'affi item link',
        'detail_img' => 'detail image',
        'amazon_asin' => 'amazon asin',
        'rakuten_id' => 'rakuten id',
        'rakuten_affiliate_url' => 'rakuten affiliate url',
        'review' => 'review',
        'is_show_url' => 1,
        'same_parent' => 1,
        'rakuten_expired_at' => '2021-01-01 00:00:00',
        'created_at' => '2022-01-01 00:00:00',
        'updated_at' => '2022-02-01 00:00:00',
        'deleted_at' => null
      ],
      $savedAdDetail->entity()
    );
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
      'rakuten affiliate url',
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
      'rakuten affiliate url',
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
      'rakuten affiliate url',
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
        'rakuten affiliate url',
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
        'rakuten affiliate url',
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
      'rakuten affiliate url',
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

  public function testRakutenLinkExpired(): void
  {
    $ad = new Ad(
      new AdId(1),
      'Main Ad Name',
      'anken-link',
      'https://www.anken.com',
      'https://www.item-link.com',
      new AspId(1),
      'banner-image.jpg',
      'image-tag-url',
      'item-image-tag-url',
      300,
      250,
      new AppId(1)
    );
    $this->adRepository->save($ad);

    $adDetailId = new AdDetailId(2);
    $adId = new AdId(1);
    $adDetail = new AdDetail(
      $adDetailId,
      $adId,
      'item name',
      'official item link',
      'affi item link',
      'detail image',
      'amazon asin',
      'rakuten id',
      'rakuten affiliate url',
      'review',
      1,
      1
    );

    $this->repository->save($adDetail);

    $adDetailId = new AdDetailId(1);
    $adId = new AdId(1);
    $adDetail = new AdDetail(
      $adDetailId,
      $adId,
      'item name',
      'official item link',
      'affi item link',
      'detail image',
      'amazon asin',
      'rakuten id',
      'rakuten affiliate url',
      'review',
      1,
      1,
      '2021-01-01 00:00:00'
    );

    $this->repository->save($adDetail);
    $res = $this->repository->findRakutenLinkExpired();
    $this->assertCount(1, $res);
    $this->assertEquals(new AdDetail(
      $adDetailId,
      $adId,
      'Main Ad Name item name',
      'official item link',
      'affi item link',
      'detail image',
      'amazon asin',
      'rakuten id',
      'rakuten affiliate url',
      'review',
      1,
      1,
      '2021-01-01 00:00:00'
    ), $res[0]);
  }

  public function testFindAllWithNonEmptyRakutenId()
  {
    $adDetailId = new AdDetailId(1);
    $adId = new AdId();
    $adDetail1 = new AdDetail(
      $adDetailId,
      $adId,
      'item name',
      'official item link',
      'affi item link',
      'detail image',
      'amazon asin',
      'rakuten id',
      'rakuten affiliate url',
      'review',
      1,
      1
    );

    $this->repository->save($adDetail1);
    $adDetailId = new AdDetailId(2);
    $adId = new AdId();
    $adDetail2 = new AdDetail(
      $adDetailId,
      $adId,
      'item name',
      'official item link',
      'affi item link',
      'detail image',
      'amazon asin',
      '',
      '',
      'review',
      1,
      1
    );

    $this->repository->save($adDetail2);
    $res = $this->repository->findAllWithNonEmptyRakutenId();
    $this->assertCount(1, $res);
    $this->assertEquals($adDetail1, $res[0]);
  }
}
