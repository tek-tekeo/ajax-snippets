<?php

use AjaxSnippets\Api\Application\Ad\AdDeleteService;
use AjaxSnippets\Api\Domain\Models\Ad\IAdRepository;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Domain\Models\Ad\Ad;
use AjaxSnippets\Api\Domain\Models\App\AppId;
use AjaxSnippets\Api\Application\Ad\AdDeleteCommand;

class AdDeleteServiceTest extends WP_UnitTestCase
{
  private IAdRepository $adRepository;
  private AdDeleteService $adDeleteService;

  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    parent::setUp();
    $this->adRepository = $diContainer->get(IAdRepository::class);
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ads");
    $this->adDeleteService = new AdDeleteService($this->adRepository);
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
        'aspName',
        'affiImg',
        'imgTag',
        'sImgTag',
        300,
        250,
        new AppId(1)
      )
    );
    
    $request = new \WP_REST_Request();
    $request->set_param('id', 1);
    $command = new AdDeleteCommand($request);
    $result = $this->adDeleteService->handle($command);
    $this->assertTrue($result);
  }
}