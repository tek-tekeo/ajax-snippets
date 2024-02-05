<?php

use AjaxSnippets\Api\Application\Asp\AspUpdateService;
use AjaxSnippets\Api\Domain\Models\Asp\IAspRepository;
use AjaxSnippets\Api\Domain\Models\Asp\AspId;
use AjaxSnippets\Api\Domain\Models\Asp\Asp;
use AjaxSnippets\Api\Application\Asp\AspUpdateCommand;
use AjaxSnippets\Api\Domain\Services\AspService;

class AspApplicationUpdateServiceTest extends WP_UnitTestCase
{
  private IAspRepository $aspRepository;
  private AspUpdateService $aspUpdateService;
  private AspService $aspService;

  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    parent::setUp();
    $this->aspRepository = $diContainer->get(IAspRepository::class);
    $this->aspService = new AspService($this->aspRepository);
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "asp");
    $this->aspUpdateService = new AspUpdateService($this->aspRepository,$this->aspService);
  }

  public function testupdate()
  {
    $this->aspRepository->save(new Asp(new AspId(), 'aspName1', 'connectString1'));
    $this->aspRepository->save(new Asp(new AspId(), 'aspName2', 'connectString2'));
    $this->aspRepository->save(new Asp(new AspId(), 'aspName3', 'connectString3'));
    $this->aspRepository->save(new Asp(new AspId(), 'aspName4', 'connectString4'));

    $request = new \WP_REST_Request();
    $request->set_param('id', '4');
    $request->set_param('aspName', 'aspName100');
    $request->set_param('connectString', 'connectString100');
    $command = new AspUpdateCommand($request);
    $aspId = $this->aspUpdateService->handle($command);
    $this->assertEquals(new AspId(4), $aspId);

    // きちんと更新されたか確認
    $res = $this->aspRepository->get(new AspId(4));
    $expected = new Asp(new AspId(4), 'aspName100', 'connectString100');
    $this->assertEquals($expected, $res);
  }
}