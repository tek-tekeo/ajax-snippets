<?php

use AjaxSnippets\Api\Application\Ad\AdDeleteService;
use AjaxSnippets\Api\Domain\Models\Ad\IAdRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailRepository;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Domain\Models\Ad\Ad;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;
use AjaxSnippets\Api\Domain\Models\App\AppId;
use AjaxSnippets\Api\Domain\Models\Asp\AspId;
use AjaxSnippets\Api\Application\Ad\AdDeleteCommand;

class AdDeleteServiceTest extends WP_UnitTestCase
{
  private IAdRepository $adRepository;
  private IAdDetailRepository $adDetailRepository;
  private AdDeleteService $adDeleteService;

  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    parent::setUp();
    $this->adRepository = $diContainer->get(IAdRepository::class);
    $this->adDetailRepository = $diContainer->get(IAdDetailRepository::class);
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ads");
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ad_details");
    $this->adDeleteService = new AdDeleteService($this->adRepository, $this->adDetailRepository);
  }

  public function testTrue()
  {
    $this->assertTrue(true);
  }

  public function testHandle()
  {
    $adId = $this->adRepository->save(
      new Ad(
        new AdId(1),
        'name',
        'anken',
        'affiLink',
        'sLink',
        new AspId(1),
        'affiImg',
        'imgTag',
        'sImgTag',
        300,
        250,
        new AppId(1)
      )
    );

    $adDetail = new AdDetail(
      new AdDetailId(1),
      $adId,
      'item name',
      'official item link',
      'affi item link',
      'detail image',
      'amazon asin',
      'rakuten id',
      '[]',
      '[]',
      'review',
      1,
      1
    );
    $this->adDetailRepository->save($adDetail);
    
    $request = new \WP_REST_Request();
    $request->set_param('id', 1);
    $command = new AdDeleteCommand($request);
    $result = $this->adDeleteService->handle($command);
    $this->assertTrue($result);

    $adDetails = $this->adDetailRepository->findByAdId(new AdId(1));
    $this->assertEquals([], $adDetails);
  }
}