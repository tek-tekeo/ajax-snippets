<?php
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailReview;
use AjaxSnippets\Api\Infrastructure\Repository\AdDetailReviewRepository;

final class AdDetailReviewRepositoryTest extends WP_UnitTestCase
{
  private $repository;

	public function setUp():void
	{
		parent::setUp();
		$this->resetDatabase();
		$this->repository = new AdDetailReviewRepository();
	}

	protected function resetDatabase()
	{
		global $wpdb;
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ad_detail_reviews");
    $wpdb->insert(PLUGIN_DB_PREFIX . 'ad_detail_reviews', [
      'id' => 0,
      'ad_detail_id' => 1,
      'name' => '匿名1',
      'sex' => '男性',
      'age' => 10,
      'rate' => 5,
      'content' => 'コンテンツ1',
      'quote_name' => 'google',
      'quote_url' => 'https://google.com',
      'is_published' => true
    ]);
    $wpdb->insert(PLUGIN_DB_PREFIX . 'ad_detail_reviews', [
      'id' => 0,
      'ad_detail_id' => 1,
      'name' => '匿名2',
      'sex' => '女性',
      'age' => 20,
      'rate' => 1,
      'content' => 'コンテンツ2',
      'quote_name' => 'instagram',
      'quote_url' => 'https://instagram.com',
      'is_published' => true
    ]);
	}

  public function testSaveAdDetailReview()
  {
    $adDetailId = new AdDetailId();
    $review = new AdDetailReview(
      0,
      $adDetailId,
      '匿名',
      20,
      '男性',
      4.5,
      'コンテンツ',
      'google',
      'https://google.com',
      true
    );

    $insertId = $this->repository->save($review);
    $this->assertEquals(3, $insertId);
  }

  public function testFindByAdDetailId()
  {
    $adDetailId = new AdDetailId(1);
    $res = $this->repository->findByAdDetailId($adDetailId);

    $this->assertInstanceOf(AdDetailReview::class, $res[0]);
    $this->assertEquals(
      [
        new AdDetailReview(1, new AdDetailId(1), '匿名1',10, '男性',  5, 'コンテンツ1', 'google', 'https://google.com',true),
        new AdDetailReview(2, new AdDetailId(1),'匿名2', 20,'女性',  1, 'コンテンツ2', 'instagram', 'https://instagram.com', true),
      ],
      $res
    );
  }

}