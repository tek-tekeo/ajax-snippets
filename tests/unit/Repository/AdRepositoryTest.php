<?php
use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Domain\Models\Ad\Ad;
use AjaxSnippets\Api\Infrastructure\Repository\AdRepository;
use AjaxSnippets\Api\Domain\Models\App\AppId;

final class AdRepositoryTest extends WP_UnitTestCase
{
  private $repository;

	public function setUp():void
	{
		parent::setUp();
		$this->resetDatabase();
		$this->repository = new AdRepository();
	}

	protected function resetDatabase()
	{
		global $wpdb;
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ads");
	}

  public function testSaveAd()
  {
    $adId = new AdId();
    $ad = new Ad(
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

    $insertId = $this->repository->save($ad);
    $this->assertEquals(new AdId(1), $insertId);
  }

  public function testFindAllAds()
  {
    $adId = new AdId();
    $ad = new Ad(
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
    $this->repository->save($ad);
    $this->repository->save($ad);
    $this->repository->save($ad);

    $res = $this->repository->getAllAds();
    $this->assertEquals([
      new Ad(
        new AdId(1),
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
      ),
      new Ad(
        new AdId(2),
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
      ),
      new Ad(
        new AdId(3),
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
      )
    ], $res);
  }

  public function testFindAd()
  {
    $newAdId = new AdId();
    $ad = new Ad(
      $newAdId,
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

    $insertId = $this->repository->save($ad);
    $findAdById = $this->repository->findById($insertId);
    $this->assertEquals(
      new Ad(
        new AdId(1),
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
      )
      , $findAdById
    );

    $secondAd = new Ad(
      $newAdId,
      'Main Ad Name2',
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
    $insertId = $this->repository->save($secondAd);

    // 名前がヒットするものがない場合
    $findAdByName = $this->repository->findByName('Dont find name');
    $this->assertEquals([], $findAdByName);

    // 2件名前がヒットする場合
    $findAdByName = $this->repository->findByName('Main Ad Name');
    $this->assertEquals(2, count($findAdByName));
  }
}