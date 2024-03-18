<?php
namespace AjaxSnippets\Api\Application\AdDetail;

use AjaxSnippets\Api\Domain\Models\TagLink\TagLink;
use AjaxSnippets\Api\Domain\Models\TagLink\TagLinkId;
use AjaxSnippets\Api\Domain\Models\Tag\TagId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailChart;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailInfo;
use AjaxSnippets\Api\Domain\Models\Ad\Ad;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailRepository;
use AjaxSnippets\Api\Domain\Models\Ad\IAdRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailChartRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailInfoRepository;
use AjaxSnippets\Api\Domain\Models\TagLink\ITagLinkRepository;

class AdDetailUpdateService implements IAdDetailUpdateService
{

  public function __construct(
    private IAdRepository $adRepository,
    private IAdDetailRepository $adDetailRepository,
    private IAdDetailChartRepository $adDetailChartRepository,
    private IAdDetailInfoRepository $adDetailInfoRepository,
    private ITagLinkRepository $tagLinkRepository
  ){}

  public function handle(AdDetailUpdateCommand $cmd) : AdDetailId
  {
    $adDetailId = new AdDetailId($cmd->getId());
    $adDetail = $this->adDetailRepository->findById($adDetailId);

    $updateAdDetail = new AdDetail(
      $adDetailId,
      ($cmd->getAdId()) ? new AdId($cmd->getAdId()) : $adDetail->getAdId(),
      ($cmd->getItemName()) ? $cmd->getItemName() : $adDetail->getItemName(),
      ($cmd->getOfficialItemLink()) ? $cmd->getOfficialItemLink() : $adDetail->getOfficialItemLink(),
      ($cmd->getAffiItemLink()) ? $cmd->getAffiItemLink() : $adDetail->getAffiItemLink(),
      ($cmd->getDetailImg()) ? $cmd->getDetailImg() : $adDetail->getDetailImg(),
      ($cmd->getAmazonAsin()) ? $cmd->getAmazonAsin() : $adDetail->getAmazonAsin(),
      ($cmd->getRakutenId()) ? $cmd->getRakutenId() : $adDetail->getRakutenId(),
      '',//($cmd->getRchart()) ? $cmd->getRchart() : $adDetail->getRchart(),
      '',//($cmd->getInfo()) ? $cmd->getInfo() : $adDetail->getInfo(),
      ($cmd->getReview()) ? $cmd->getReview() : $adDetail->getReview(),
      $cmd->getIsShowUrl(),
      $cmd->getSameParent()
    );

    $insertAdDetailId = $this->adDetailRepository->save($updateAdDetail);

    $this->tagLinkRepository->delete($insertAdDetailId);
    $res = collect($cmd->getTagIds())->map(function($tagId) use ($insertAdDetailId){
      $tagLink = new TagLink(
        new TagLinkId(),
        $insertAdDetailId,
        new TagId($tagId)
      );
      return $this->tagLinkRepository->update($tagLink);
    })->toArray();

    $this->adDetailChartRepository->deleteByAdDetailId($insertAdDetailId);
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

    $this->adDetailInfoRepository->deleteByAdDetailId($insertAdDetailId);
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