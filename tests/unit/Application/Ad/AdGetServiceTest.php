<?php

use AjaxSnippets\Api\Application\Ad\AdGetService;
use AjaxSnippets\Api\Domain\Models\Ad\IAdRepository;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Domain\Models\Ad\Ad;
use AjaxSnippets\Api\Domain\Models\App\AppId;
use AjaxSnippets\Api\Application\Ad\AdGetCommand;
use AjaxSnippets\Api\Application\DTO\Ad\AdData;

class AdGetServiceTest extends WP_UnitTestCase
{
  private IAdRepository $adRepository;
  private AdGetService $adGetService;
  private Ad $ad;
  private array $columns;

  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    parent::setUp();
    $this->adRepository = $diContainer->get(IAdRepository::class);
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ads");
    $this->adGetService = new AdGetService($this->adRepository);
    $this->columns = [
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
    ];
    $this->ad = new Ad(
      new AdId(),
      ...$this->columns
    );
  }

  public function testGetAd()
  {
    $this->adRepository->save($this->ad);
    $this->adRepository->save($this->ad);
    $this->adRepository->save($this->ad);

    $request = new \WP_REST_Request();
    $request->set_param('id', 2);
    $command = new AdGetCommand($request);
    $actualAdData = $this->adGetService->handle($command);
    
    $this->assertEquals(
      new AdData(new Ad(
        new AdId(2),
      ...$this->columns
    )), $actualAdData);
  }

  public function testGetAllAds()
  {
    $this->adRepository->save($this->ad);
    $this->adRepository->save($this->ad);
    $this->adRepository->save($this->ad);

    $actualAdData = $this->adGetService->getAll();
    
    $this->assertEquals(
      [
        new AdData(new Ad(new AdId(1), ...$this->columns)),
        new AdData(new Ad(new AdId(2), ...$this->columns)),
        new AdData(new Ad(new AdId(3), ...$this->columns))
      ],
      $actualAdData
    );
  }

  public function testFindByName()
  {
    $findAd = new Ad(
      new AdId(3),
      'Find Name',
      'anken-link',
      'https://www.anken.com',
      'https://www.item-link.com',
      'a8',
      'banner-image.jpg',
      'image-tag-url',
      'item-image-tag-url',
      300,
      250,
      new AppId(1)
    );
    $this->adRepository->save($this->ad);
    $this->adRepository->save($this->ad);
    $this->adRepository->save($findAd); // This is the ad we want to find
    $this->adRepository->save($this->ad);
    $actualAdData = $this->adGetService->getAdsByName('Find');
    

    $this->assertEquals([new AdData($findAd)], $actualAdData);
  }

}