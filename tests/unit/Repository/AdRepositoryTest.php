<?php

use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Domain\Models\Ad\Ad;
use AjaxSnippets\Api\Infrastructure\Repository\AdRepository;
use AjaxSnippets\Api\Domain\Models\App\AppId;
use AjaxSnippets\Api\Domain\Models\Asp\AspId;

final class AdRepositoryTest extends WP_UnitTestCase
{
  private $repository;
  private $table;
  private $db;

  public function setUp(): void
  {
    parent::setUp();
    $this->resetDatabase();
    $this->repository = new AdRepository();
  }

  protected function resetDatabase()
  {
    global $wpdb;
    $this->db = $wpdb;
    $this->table = PLUGIN_DB_PREFIX . 'ads';
    $wpdb->query("TRUNCATE TABLE " . $this->table);
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
      new AspId(1),
      'banner-image.jpg',
      'image-tag-url',
      'item-image-tag-url',
      300,
      250,
      new AppId()
    );

    $insertId = $this->repository->save($ad);
    $this->assertEquals(new AdId(1), $insertId);

    // 削除の確認
    $res = $this->repository->delete($insertId);
    $this->assertTrue($res);
    $res = $this->db->get_row("SELECT * FROM " . $this->table . " WHERE id = " . $insertId->getId());
    $this->assertEquals(
      [
        'id' => 1,
        'name' => 'Main Ad Name',
        'anken' => 'anken-link',
        'affi_link' => 'https://www.anken.com',
        's_link' => 'https://www.item-link.com',
        'asp_id' => 1,
        'affi_img' => 'banner-image.jpg',
        'img_tag' => 'image-tag-url',
        's_img_tag' => 'item-image-tag-url',
        'affi_img_width' => 300,
        'affi_img_height' => 250,
        'app_id' => 0,
        'deleted_at' => date('Y-m-d')
      ],
      (array)$res
    );
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
      new AspId(1),
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

    $res = $this->repository->findAll();
    $this->assertEquals([
      new Ad(
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
        new AppId()
      ),
      new Ad(
        new AdId(2),
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
        new AppId()
      ),
      new Ad(
        new AdId(3),
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
      new AspId(1),
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
        new AspId(1),
        'banner-image.jpg',
        'image-tag-url',
        'item-image-tag-url',
        300,
        250,
        new AppId()
      ),
      $findAdById
    );

    $secondAd = new Ad(
      $newAdId,
      'Main Ad Name2',
      'anken-link',
      'https://www.anken.com',
      'https://www.item-link.com',
      new AspId(1),
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
