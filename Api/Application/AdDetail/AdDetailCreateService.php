<?php
namespace AjaxSnippets\Api\Application\AdDetail;

use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Application\AdDetail\AdDetailCreateCommand;
use AjaxSnippets\Api\Domain\Models\TagLink\ITagLinkRepository;
use AjaxSnippets\Api\Domain\Models\TagLink\TagLinkId;
use AjaxSnippets\Api\Domain\Models\TagLink\TagLink;
use AjaxSnippets\Api\Domain\Models\Tag\TagId;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailChartRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailInfoRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailChart;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailInfo;

class AdDetailCreateService implements IAdDetailCreateService
{
  public function __construct(
    private IAdDetailRepository $detailRepository,
    private IAdDetailChartRepository $adDetailChartRepository,
    private IAdDetailInfoRepository $adDetailInfoRepository,
    private ITagLinkRepository $tagLinkRepository
  ){}

  public function handle(AdDetailCreateCommand $cmd): AdDetailId
  {
    $adDetail = new AdDetail(
      new AdDetailId(),
      new AdId($cmd->getAdId()),
      $cmd->getItemName(),
      $cmd->getOfficialItemLink(),
      $cmd->getAffiItemLink(),
      $cmd->getDetailImg(),
      $cmd->getAmazonAsin(),
      $cmd->getRakutenId(),
      '',//$cmd->getRchart(),
      '',//$cmd->getInfo(),
      $cmd->getReview(),
      $cmd->getIsShowUrl(),
      $cmd->getSameParent()
    );
    $insertAdDetailId = $this->detailRepository->save($adDetail);
    collect($cmd->getTagIds())->map(function($tagId) use ($insertAdDetailId){
      $this->tagLinkRepository->save(
        new TagLink(
          new TagLinkId(),
          $insertAdDetailId,
          new TagId($tagId)
        )
      );
    });

    $res = collect($cmd->getRates())->map(function($chart) use ($insertAdDetailId){
      $adDetailChart = new AdDetailChart(
        0,
        $insertAdDetailId,
        $chart['factor'],
        $chart['value'],
        $chart['sortOrder'] ?? 0
      );
      return $this->adDetailChartRepository->save($adDetailChart);
    })->toArray();

    $res = collect($cmd->getInfos())->map(function($info) use ($insertAdDetailId){
      $adDetailInfo = new AdDetailInfo(
        0,
        $insertAdDetailId,
        $info['factor'],
        $info['value'],
        $info['sortOrder'] ?? 0
      );
      return $this->adDetailInfoRepository->save($adDetailInfo);
    })->toArray();

    return $insertAdDetailId;
  }
}