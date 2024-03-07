<?php

use AjaxSnippets\Api\Application\AdDetail\AdDetailDeleteService;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Application\AdDetail\AdDetailDeleteCommand;

class AdDetailDeleteServiceTest extends WP_UnitTestCase
{
  private IAdDetailRepository $adDetailRepository;
  private AdDetailDeleteService $adDetailDeleteService;

  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    parent::setUp();
    $this->adDetailRepository = $diContainer->get(IAdDetailRepository::class);
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ad_details");
    $this->adDetailDeleteService = new AdDetailDeleteService($this->adDetailRepository);
  }

  public function testTrue()
  {
    $this->assertTrue(true);
  }

  public function testHandle()
  {
    $adDetailId = $this->adDetailRepository->save(
      new AdDetail(
        new AdDetailId(1),
        new AdId(1),
        'itemName',
        'officialItemLink',
        'affiItemLink',
        'detailImg',
        'amazonAsin',
        'rakutenId',
        'rchart',
        'info',
        'review',
        1,
        1
      )
    );
    
    $request = new \WP_REST_Request();
    $request->set_param('id', 1);
    $command = new AdDetailDeleteCommand($request);
    $result = $this->adDetailDeleteService->handle($command);
    $this->assertTrue($result);
  }
}