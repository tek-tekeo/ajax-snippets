<?php

namespace AjaxSnippets\Api\Application\AdDetail;

use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;
use AjaxSnippets\Api\Application\AdDetail\AdDetailCreateCommand;
use AjaxSnippets\Api\Application\AdDetail\AdDetailReviewCreateCommand;
use AjaxSnippets\Api\Domain\Models\TagLink\ITagLinkRepository;
use AjaxSnippets\Api\Domain\Models\TagLink\TagLinkId;
use AjaxSnippets\Api\Domain\Models\TagLink\TagLink;
use AjaxSnippets\Api\Domain\Models\Tag\TagId;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailChartRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailInfoRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailReviewRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailChart;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailInfo;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailReview;
use AjaxSnippets\Api\Infrastructure\Services\RakutenAffiliateService;

class AdDetailCreateService implements IAdDetailCreateService
{
  public function __construct(
    private IAdDetailRepository $detailRepository,
    private IAdDetailChartRepository $adDetailChartRepository,
    private IAdDetailInfoRepository $adDetailInfoRepository,
    private IAdDetailReviewRepository $adDetailReviewRepository,
    private ITagLinkRepository $tagLinkRepository
  ) {}

  public function handle(AdDetailCreateCommand $cmd): AdDetailId
  {
    $rakutenId = $cmd->getRakutenId();
    $rakutenAffiliateUrl = '';
    $rakutenExpredAt = null;

    if ($rakutenId != '') {
      $rakutenAffiliateService = new RakutenAffiliateService();
      $res = $rakutenAffiliateService->checkRakutenId($rakutenId);
      $rakutenAffiliateUrl = $res['affiliateUrl'];
      if ($res['success']) {
        $rakutenExpredAt = null;
      } else {
        $rakutenExpredAt = date('Y-m-d H:i:s');
      }
    }

    $adDetail = new AdDetail(
      new AdDetailId(),
      new AdId($cmd->getAdId()),
      $cmd->getItemName(),
      $cmd->getOfficialItemLink(),
      $cmd->getAffiItemLink(),
      $cmd->getDetailImg(),
      $cmd->getAmazonAsin(),
      $cmd->getRakutenId(),
      $rakutenAffiliateUrl,
      $cmd->getReview(),
      $cmd->getIsShowUrl(),
      $cmd->getSameParent(),
      $rakutenExpredAt,
      date('Y-m-d H:i:s'),
      date('Y-m-d H:i:s'),
      null
    );

    $insertAdDetailId = $this->detailRepository->save($adDetail);
    collect($cmd->getTagIds())->map(function ($tagId) use ($insertAdDetailId) {
      $this->tagLinkRepository->save(
        new TagLink(
          new TagLinkId(),
          $insertAdDetailId,
          new TagId($tagId)
        )
      );
    });

    $res = collect($cmd->getRates())->map(function ($chart) use ($insertAdDetailId) {
      $adDetailChart = new AdDetailChart(
        0,
        $insertAdDetailId,
        $chart['factor'],
        $chart['value'],
        $chart['sortOrder'] ?? 0
      );
      return $this->adDetailChartRepository->save($adDetailChart);
    })->toArray();

    $res = collect($cmd->getInfos())->map(function ($info) use ($insertAdDetailId) {
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

  public function handleReview(AdDetailReviewCreateCommand $cmd): int
  {
    $review = new AdDetailReview(
      0,
      new AdDetailId($cmd->getAdDetailId()),
      $cmd->getName(),
      $cmd->getAge(),
      $cmd->getSex(),
      (float)$cmd->getRatingValue(),
      $cmd->getContent(),
      $cmd->getQuoteName(),
      $cmd->getQuoteUrl(),
      false,
      $cmd->getCreatedAt(),
      $cmd->getUpdatedAt()
    );
    return $this->adDetailReviewRepository->save($review);
  }
}
