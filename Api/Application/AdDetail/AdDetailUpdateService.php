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
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailReviewRepository;
use AjaxSnippets\Api\Domain\Models\TagLink\ITagLinkRepository;
use AjaxSnippets\Api\Application\AdDetail\AdDetailReviewUpdateCommand;
use AjaxSnippets\Api\Application\AdDetail\IAdDetailUpdateService;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailReview;
use AjaxSnippets\Api\Infrastructure\Services\RakutenAffiliateService;

class AdDetailUpdateService implements IAdDetailUpdateService
{

  public function __construct(
    private IAdRepository $adRepository,
    private IAdDetailRepository $adDetailRepository,
    private IAdDetailChartRepository $adDetailChartRepository,
    private IAdDetailInfoRepository $adDetailInfoRepository,
    private IAdDetailReviewRepository $adDetailReviewRepository,
    private ITagLinkRepository $tagLinkRepository
  ) {}

  public function handle(AdDetailUpdateCommand $cmd): int
  {
    $adDetailId = new AdDetailId($cmd->getId());
    $adDetail = $this->adDetailRepository->findByIdWithDelete($adDetailId);

    $rakutenId = $cmd->getRakutenId();
    $rakutenAffiliateUrl = $adDetail->getRakutenAffiliateUrl();
    $rakutenExpredAt = $adDetail->getRakutenExpiredAt();

    if ($cmd->getRakutenId() == '') {
      $rakutenAffiliateUrl = '';
      $rakutenExpredAt = null;
    } elseif ($adDetail->getRakutenExpiredAt() == null && $rakutenId == $adDetail->getRakutenId()) {
      $rakutenAffiliateUrl = $adDetail->getRakutenAffiliateUrl();
      $rakutenExpredAt = null;
    } else {
      $rakutenAffiliateService = new RakutenAffiliateService();
      $res = $rakutenAffiliateService->checkRakutenId($rakutenId);
      $rakutenAffiliateUrl = $res['affiliateUrl'];
      if ($res['success']) {
        $rakutenExpredAt = null;
      } else {
        $rakutenExpredAt = date('Y-m-d H:i:s');
      }
    }

    $updateAdDetail = new AdDetail(
      $adDetailId,
      ($cmd->getAdId()) ? new AdId($cmd->getAdId()) : $adDetail->getAdId(),
      ($cmd->getItemName()) ? $cmd->getItemName() : $adDetail->getItemName(),
      ($cmd->getOfficialItemLink()) ? $cmd->getOfficialItemLink() : $adDetail->getOfficialItemLink(),
      ($cmd->getAffiItemLink()) ? $cmd->getAffiItemLink() : $adDetail->getAffiItemLink(),
      $cmd->getDetailImg(),
      $cmd->getAmazonAsin(),
      $rakutenId,
      $rakutenAffiliateUrl,
      $cmd->getReview(),
      $cmd->getIsShowUrl(),
      $cmd->getSameParent(),
      $rakutenExpredAt,
      $adDetail->getCreatedAt(),
      date("Y-m-d H:i:s"),
      $adDetail->getDeletedAt()
    );

    $insertAdDetailId = $this->adDetailRepository->save($updateAdDetail);

    $this->tagLinkRepository->delete($insertAdDetailId);
    $res = collect($cmd->getTagIds())->map(function ($tagId) use ($insertAdDetailId) {
      $tagLink = new TagLink(
        new TagLinkId(),
        $insertAdDetailId,
        new TagId($tagId)
      );
      return $this->tagLinkRepository->update($tagLink);
    })->toArray();

    $this->adDetailChartRepository->deleteByAdDetailId($insertAdDetailId);
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

    $this->adDetailInfoRepository->deleteByAdDetailId($insertAdDetailId);
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

    return $insertAdDetailId->getId();
  }

  public function handleReview(AdDetailReviewUpdateCommand $cmd): int
  {
    $reviewId = $cmd->getId();
    $review = $this->adDetailReviewRepository->findById($reviewId);

    $updateAdDetailReview = new AdDetailReview(
      $cmd->getId(),
      new AdDetailId($cmd->getAdDetailId()),
      $cmd->getName(),
      $cmd->getAge(),
      $cmd->getSex(),
      $cmd->getRatingValue(),
      $cmd->getContent(),
      $cmd->getQuoteName(),
      $cmd->getQuoteUrl(),
      $cmd->getIsPublished(),
      $review->getCreatedAt(),
      date("Y-m-d H:i:s")
    );

    $insertAdDetaiReviewlId = $this->adDetailReviewRepository->save($updateAdDetailReview);

    return $insertAdDetaiReviewlId;
  }

  public function updateRakutenExpiredAt($id, $rakutenId)
  {
    $adDetail = $this->adDetailRepository->findByIdWithDelete(new AdDetailId($id));
    $rakutenExpredAt = $adDetail->getRakutenExpiredAt();
    if ($rakutenId == '') {
      $res = ['success' => true, 'text' => '楽天商品リンクを空にして更新しました', 'affiliateUrl' => ''];
      $rakutenExpredAt = null;
    } else if ($adDetail->getRakutenExpiredAt() == null && $rakutenId == $adDetail->getRakutenId()) {
      $res = ['success' => true, 'text' => '以前と同じ楽天商品リンクです'];
      return $res;
    } else {
      $rakutenAffiliateService = new RakutenAffiliateService();
      $res = $rakutenAffiliateService->checkRakutenId($rakutenId);
      if ($res['success']) {
        $rakutenExpredAt = null;
      } else {
        $rakutenExpredAt = date('Y-m-d H:i:s');
      }
    }

    // 楽天商品リンクが正常であれば、楽天商品リンクの有効期限を更新する

    $newAdDetail = new AdDetail(
      $adDetail->getId(),
      $adDetail->getAdId(),
      $adDetail->getItemName(),
      $adDetail->getOfficialItemLink(),
      $adDetail->getAffiItemLink(),
      $adDetail->getDetailImg(),
      $adDetail->getAmazonAsin(),
      $rakutenId,
      $res['affiliateUrl'],
      $adDetail->getReview(),
      $adDetail->getIsShowUrl(),
      $adDetail->getSameParent(),
      $rakutenExpredAt,
      $adDetail->getCreatedAt(),
      date('Y-m-d H:i:s'),
      $adDetail->getDeletedAt()
    );
    $this->adDetailRepository->save($newAdDetail);
    return $res;
  }
}
