<?php

use AjaxSnippets\Api\Application\AdDetail\AdDetailGetService;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailRepository;
use AjaxSnippets\Api\Domain\Models\Ad\IAdRepository;
use AjaxSnippets\Api\Domain\Models\Asp\IAspRepository;
use AjaxSnippets\Api\Domain\Models\TagLink\ITagLinkRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailChartRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailInfoRepository;
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
use AjaxSnippets\Api\Application\DTO\Ad\AdDetailDataIndex;
use AjaxSnippets\Api\Application\DTO\Ad\EditDetailData;
use AjaxSnippets\Api\Application\DTO\Ad\AffiLinkData;
use AjaxSnippets\Api\Domain\Models\TagLink\TagLink;
use AjaxSnippets\Api\Domain\Models\TagLink\TagLinkId;
use AjaxSnippets\Api\Domain\Models\Tag\TagId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailChart;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailInfo;

class AdDetailGetServiceTest extends WP_UnitTestCase
{
  private IAdDetailRepository $adDetailRepository;
  private IAspRepository $aspRepository;
  private IAdRepository $adRepository;
  private IAdDetailChartRepository $adDetailChartRepository;
  private IAdDetailInfoRepository $adDetailInfoRepository;
  private AdDetailGetService $adDetailGetService;
  private AdDetail $adDetail;
  private array $columns;
  private Ad $ad;
  private ITagLinkRepository $tagLinkRepository;

  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    parent::setUp();
    $this->adDetailRepository = $diContainer->get(IAdDetailRepository::class);
    $this->adRepository = $diContainer->get(IAdRepository::class);
    $this->aspRepository = $diContainer->get(IAspRepository::class);
    $this->tagLinkRepository = $diContainer->get(ITagLinkRepository::class);
    $this->adDetailChartRepository = $diContainer->get(IAdDetailChartRepository::class);
    $this->adDetailInfoRepository = $diContainer->get(IAdDetailInfoRepository::class);
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "asps");
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ad_details");
    $wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ad_details_chart");
    $wpdb->insert(PLUGIN_DB_PREFIX . 'ad_details_chart', [
      'id' => 1,
      'ad_detail_id' => 2,
      'factor' => 'おすすめ度',
      'rate' => 4.4,
      'sort_order' => 2,
    ]);
    $wpdb->insert(PLUGIN_DB_PREFIX . 'ad_details_chart', [
      'id' => 2,
      'ad_detail_id' => 2,
      'factor' => 'ダメダメ度',
      'rate' => 1.1,
      'sort_order' => 1,
    ]);
    $wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ad_details_info");
    $wpdb->insert(PLUGIN_DB_PREFIX . 'ad_details_info', [
      'id' => 1,
      'ad_detail_id' => 2,
      'title' => 'URL',
      'content' => 'https://www.example.com',
      'sort_order' => 2,
    ]);
    $wpdb->insert(PLUGIN_DB_PREFIX . 'ad_details_info', [
      'id' => 2,
      'ad_detail_id' => 2,
      'title' => '販売元',
      'content' => 'まるまる商会',
      'sort_order' => 1,
    ]);
    $wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "tag_link");
    $this->adDetailGetService = new AdDetailGetService(
      $this->adRepository,
      $this->adDetailRepository,
      $this->adDetailChartRepository,
      $this->adDetailInfoRepository,
      $this->aspRepository,
      $this->tagLinkRepository
    );

    $this->adRepository = $diContainer->get(IAdRepository::class);
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ads");

    $this->ad = new Ad(
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
      '',
      '',
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

    $this->tagLinkRepository->save(
      new TagLink(
        new TagLinkId(),
        new AdDetailId(2),
        new TagId(1)
      )
    );

    $this->tagLinkRepository->save(
      new TagLink(
        new TagLinkId(),
        new AdDetailId(2),
        new TagId(2)
      )
    );
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
        ),
        [
          new AdDetailChart(
            2,
            new AdDetailId(2),
            'ダメダメ度',
            1.1,
            1
          ),
          new AdDetailChart(
            1,
            new AdDetailId(2),
            'おすすめ度',
            4.4,
            2
          ),
        ],
        [
          new AdDetailInfo(
            2,
            new AdDetailId(2),
            '販売元',
            'まるまる商会',
            1
          ),
          new AdDetailInfo(
            1,
            new AdDetailId(2),
            'URL',
            'https://www.example.com',
            2
          )
        ],
        [
          new TagLink(new TagLinkId(1), new AdDetailId(2), new TagId(1)),
          new TagLink(new TagLinkId(2), new AdDetailId(2), new TagId(2))
        ]
    );
    $this->assertEquals($expected, $actualAdDetailData);
  }

  public function testGetAllAdDetails()
  {
    $actualAdDetailData = $this->adDetailGetService->getAdDetailsFindByName('dddddd');
    $this->assertEquals([], $actualAdDetailData);

    $actualAdDetailData = $this->adDetailGetService->getAdDetailsFindByName('');
    $this->assertEquals(
      [
        new AdDetailDataIndex($this->ad,new AdDetail(
          new AdDetailId(1),
          ...$this->columns
        )),
        new AdDetailDataIndex($this->ad,new AdDetail(
          new AdDetailId(2),
          ...$this->columns
        )),
        new AdDetailDataIndex($this->ad,new AdDetail(
          new AdDetailId(3),
          ...$this->columns
        ))
      ],
      $actualAdDetailData
    );
  }

  public function testGetEditorAnkenList()
  {
    global $wpdb;
		$wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "asps");

    $asp = new Asp(
      new AspId(1),
      'aspName',
      'link'
    );
    $actualAdDetailData = $this->adDetailGetService->getEditorAnkenList('');
    $expected = [
      new EditDetailData(
        $this->ad,
        new AdDetail(new AdDetailId(1), ...$this->columns),
        new Asp(new AspId(0), '未設定', '')
      ),
      new EditDetailData(
        $this->ad,
        new AdDetail(new AdDetailId(2), ...$this->columns),
        new Asp(new AspId(0), '未設定', '')
      ),
      new EditDetailData(
        $this->ad,
        new AdDetail(new AdDetailId(3), ...$this->columns),
        new Asp(new AspId(0), '未設定', '')
      )
    ];
    $this->assertEquals($expected, $actualAdDetailData);

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