<?php

use AjaxSnippets\Api\Application\AdDetail\AdDetailGetService;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailRepository;
use AjaxSnippets\Api\Domain\Models\Ad\IAdRepository;
use AjaxSnippets\Api\Domain\Models\Asp\IAspRepository;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Domain\Models\Ad\Ad;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;
use AjaxSnippets\Api\Domain\Models\Asp\Asp;
use AjaxSnippets\Api\Domain\Models\Asp\AspId;
use AjaxSnippets\Api\Domain\Models\App\AppId;
use AjaxSnippets\Api\Application\AdDetail\AdDetailGetCommand;
use AjaxSnippets\Api\Application\Ad\AdCreateService;
use AjaxSnippets\Api\Application\DTO\Ad\AdDetailData;
use AjaxSnippets\Api\Application\DTO\Ad\EditDetailData;
use AjaxSnippets\Api\Application\DTO\Ad\AffiLinkData;

class AdDetailGetServiceTest extends WP_UnitTestCase
{
  private IAdDetailRepository $adDetailRepository;
  private IAspRepository $aspRepository;
  private IAdRepository $adRepository;
  private AdDetailGetService $adDetailGetService;
  private AdDetail $adDetail;
  private array $columns;
  private Ad $ad;

  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    parent::setUp();
    $this->adDetailRepository = $diContainer->get(IAdDetailRepository::class);
    $this->adRepository = $diContainer->get(IAdRepository::class);
    $this->aspRepository = $diContainer->get(IAspRepository::class);
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ad_details");
    $this->adDetailGetService = new AdDetailGetService(
      $this->adRepository,
      $this->adDetailRepository,
      $this->aspRepository
    );

    $this->adRepository = $diContainer->get(IAdRepository::class);
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ads");

    $this->ad = new Ad(
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
    );
    $res = $this->adRepository->save($this->ad);

    $this->columns = [
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
    ];

    $this->adDetail = new AdDetail(
      new AdDetailId(),
      ...$this->columns
    );
    $this->adDetailRepository->save($this->adDetail);
    $this->adDetailRepository->save($this->adDetail);
    $this->adDetailRepository->save($this->adDetail);
  }

  public function testGetAdDetail()
  {
    $request = new \WP_REST_Request();
    $request->set_param('id', 2);
    $command = new AdDetailGetCommand($request);
    $actualAdDetailData = $this->adDetailGetService->handle($command);
    
    $expected =  new AdDetailData(
        $this->ad, 
        new AdDetail(
          new AdDetailId(2),
          ...$this->columns
      ));
    $this->assertEquals($expected, $actualAdDetailData);
  }

  public function testGetAllAdDetails()
  {
    $actualAdDetailData = $this->adDetailGetService->getDetailsFindByName('dddddd');
    $this->assertEquals([], $actualAdDetailData);

    $actualAdDetailData = $this->adDetailGetService->getDetailsFindByName('');
    $this->assertEquals(
      [
        new AdDetailData($this->ad,new AdDetail(
          new AdDetailId(1),
          ...$this->columns
        )),
        new AdDetailData($this->ad,new AdDetail(
          new AdDetailId(2),
          ...$this->columns
        )),
        new AdDetailData($this->ad,new AdDetail(
          new AdDetailId(3),
          ...$this->columns
        ))
      ],
      $actualAdDetailData
    );
  }

  public function testGetEditorAnkenList()
  {
    $asp = new Asp(
      new AspId(1),
      'aspName',
      'link'
    );
    $this->aspRepository->save($asp);
    $actualAdDetailData = $this->adDetailGetService->getEditorAnkenList('');

    $expected = [
      new EditDetailData(
        $this->ad,
        new AdDetail(new AdDetailId(1), ...$this->columns),
        $asp
      ),
      new EditDetailData(
        $this->ad,
        new AdDetail(new AdDetailId(2), ...$this->columns),
        $asp
      ),
      new EditDetailData(
        $this->ad,
        new AdDetail(new AdDetailId(3), ...$this->columns),
        $asp
      )
    ];
    $this->assertEquals($expected, $actualAdDetailData);

  }

  public function testGetLinkMaker()
  {
    $request = new \WP_REST_Request();
    $request->set_param('id', 2);
    $cmd = new AdDetailGetCommand($request);
    $actualAdDetailData = $this->adDetailGetService->getLinkMaker($cmd);
    $expected = new AffiLinkData(
      $this->ad,
      new AdDetail(new AdDetailId(2), ...$this->columns)
    );
    $this->assertEquals($expected, $actualAdDetailData);
  }

  public function testGetLatestDetail()
  {
    $asp = new Asp(
      new AspId(1),
      'aspName',
      'link'
    );
    $this->aspRepository->save($asp);
    $actualAdDetailData = $this->adDetailGetService->getLatestDetail();
    $expected = new AdDetail(
      new AdDetailId(3),
      ...$this->columns
    );
    $this->assertEquals(new AdDetailData($this->ad, $expected), $actualAdDetailData);
  }

}