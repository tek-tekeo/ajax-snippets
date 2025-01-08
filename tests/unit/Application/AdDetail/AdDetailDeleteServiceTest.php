<?php

use AjaxSnippets\Api\Application\AdDetail\AdDetailDeleteService;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Application\AdDetail\AdDetailDeleteCommand;
use AjaxSnippets\Api\Domain\Models\TagLink\ITagLinkRepository;
use AjaxSnippets\Api\Domain\Models\TagLink\TagLink;
use AjaxSnippets\Api\Domain\Models\TagLink\TagLinkId;
use AjaxSnippets\Api\Domain\Models\Tag\TagId;

class AdDetailDeleteServiceTest extends WP_UnitTestCase
{
  private IAdDetailRepository $adDetailRepository;
  private AdDetailDeleteService $adDetailDeleteService;
  private ITagLinkRepository $tagLinkRepository;

  public function setUp(): void
  {
    global $wpdb;
    global $diContainer;
    parent::setUp();
    $this->adDetailRepository = $diContainer->get(IAdDetailRepository::class);
    $this->tagLinkRepository = $diContainer->get(ITagLinkRepository::class);
    $wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "ad_details");
    $wpdb->query("TRUNCATE TABLE " . PLUGIN_DB_PREFIX . "tag_link");
    $this->adDetailDeleteService = new AdDetailDeleteService($this->adDetailRepository, $this->tagLinkRepository);
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
        'review',
        1,
        1
      )
    );
    $this->tagLinkRepository->save(
      new TagLink(
        new TagLinkId(),
        new AdDetailId(1),
        new TagId(1)
      )
    );

    $this->tagLinkRepository->save(
      new TagLink(
        new TagLinkId(),
        new AdDetailId(1),
        new TagId(2)
      )
    );

    $request = new \WP_REST_Request();
    $request->set_param('id', 1);
    $command = new AdDetailDeleteCommand($request);
    $result = $this->adDetailDeleteService->handle($command);
    $this->assertTrue($result);

    $tagLinks = $this->tagLinkRepository->findByAdDetailId(new AdDetailId(1));
    $this->assertEquals(0, count($tagLinks));

    $this->expectException(\Exception::class);
    $this->expectExceptionMessage('Ad Detail ID”1”に該当するデータが存在しません。');
    $this->expectExceptionCode(500);
    $res = $this->adDetailRepository->findById(new AdDetailId(1));
  }
}
