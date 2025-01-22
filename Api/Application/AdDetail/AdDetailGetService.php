<?php

namespace AjaxSnippets\Api\Application\AdDetail;

use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailChartRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailInfoRepository;
use AjaxSnippets\Api\Domain\Models\AdDetail\IAdDetailReviewRepository;
use AjaxSnippets\Api\Domain\Models\Ad\IAdRepository;
use AjaxSnippets\Api\Domain\Models\Asp\IAspRepository;
use AjaxSnippets\Api\Domain\Models\TagLink\ITagLinkRepository;
use AjaxSnippets\Api\Application\DTO\Ad\AdDetailData;
use AjaxSnippets\Api\Application\DTO\Ad\EditDetailData;
use AjaxSnippets\Api\Application\DTO\Ad\AffiLinkData;
use AjaxSnippets\Api\Application\DTO\Ad\AdDetailDataIndex;
use AjaxSnippets\Api\Application\DTO\Ad\AdDetailReviewData;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailInfo;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailChart;
use AjaxSnippets\Api\Domain\Models\Asp\Asp;
use AjaxSnippets\Api\Domain\Models\Asp\AspId;

class AdDetailGetService implements IAdDetailGetService
{
  public function __construct(
    private IAdRepository $adRepository,
    private IAdDetailRepository $adDetailRepository,
    private IAdDetailChartRepository $adDetailChartRepository,
    private IAdDetailInfoRepository $adDetailInfoRepository,
    private IAdDetailReviewRepository $adDetailReviewRepository,
    private IAspRepository $aspRepository,
    private ITagLinkRepository $tagLinkRepository
  ) {}

  public function handle(AdDetailGetCommand $cmd)
  {
    $detail = $this->adDetailRepository->findByIdWithDelete(new AdDetailId($cmd->getId()));
    $ad = $this->adRepository->findById($detail->getAdId());
    $adDetailInfo = $this->adDetailInfoRepository->findByAdDetailId($detail->getId());
    $adDetailChart = $this->adDetailChartRepository->findByAdDetailId($detail->getId());
    $tags = $this->tagLinkRepository->findByAdDetailId($detail->getId());
    return new AdDetailData($ad, $detail, $adDetailChart, $adDetailInfo, $tags);
  }

  public function getAdDetailsFindByName(string $name)
  {
    $details = $this->adDetailRepository->findByName($name);

    return collect($details)->map(function ($adDetail) {
      $ad = $this->adRepository->findById($adDetail->getAdId());
      return new AdDetailDataIndex($ad, $adDetail);
    })->toArray();
  }

  public function getAdDetailsFindByIdOrName(string $name)
  {
    $details = $this->adDetailRepository->findByIdOrName($name);

    return collect($details)->map(function ($adDetail) {
      $ad = $this->adRepository->findById($adDetail->getAdId());
      return new AdDetailDataIndex($ad, $adDetail);
    })->toArray();
  }

  public function getEditorAnkenList(string $name)
  {
    $details = $this->adDetailRepository->findByIdOrName($name);
    return collect($details)->map(function ($adDetail) {
      $ad = $this->adRepository->findById($adDetail->getAdId());
      try {
        $asp = $this->aspRepository->findById($ad->getAspId());
      } catch (\Exception $e) {
        $asp = new Asp(new AspId(0), '未設定', '');
      }
      return new EditDetailData($ad, $adDetail, $asp);
    })->toArray();
  }

  public function getLinkMaker(AdDetailGetCommand $cmd)
  {
    $adDetail = $this->adDetailRepository->findById(new AdDetailId($cmd->getId()));
    $ad = $this->adRepository->findById($adDetail->getAdId());
    return new AffiLinkData($ad, $adDetail);
  }

  public function getLatestDetail()
  {
    $detail = $this->adDetailRepository->findLatest();
    $ad = $this->adRepository->findById($detail->getAdId());
    $adDetailInfo = $this->adDetailInfoRepository->findByAdDetailId($detail->getId());
    $adDetailChart = $this->adDetailChartRepository->findByAdDetailId($detail->getId());
    $tags = $this->tagLinkRepository->findByAdDetailId($detail->getId());
    return new AdDetailData($ad, $detail, $adDetailChart, $adDetailInfo, $tags);
  }

  public function getReview(AdDetailGetCommand $cmd, string $sortType = 'good')
  {
    $sortOrder = $sortType === 'good' ? 'desc' : 'asc';
    $adDetailReviews = $this->adDetailReviewRepository->findByAdDetailId(new AdDetailId($cmd->getId()), 'rate', $sortOrder);
    return (new AdDetailReviewData($adDetailReviews))->handle();
  }

  public function getReviewsByAdDetailId(AdDetailGetCommand $cmd)
  {
    $adDetailReviews = $this->adDetailReviewRepository->findByAdDetailId(new AdDetailId($cmd->getId()));
    return collect($adDetailReviews)->map(function ($review) {
      return (object)[
        'id' => $review->getId(),
        'ratingValue' => $review->getRatingValue(),
        'name'  => $review->getName(),
        'age'   => $review->getAge(),
        'sex' => $review->getSex(),
        'title' => $review->getTitle(),
        'content' => $review->getContent(),
        'quoteName' => $review->getQuoteName(),
        'quoteUrl' => $review->getQuoteUrl(),
        'isPublished' => $review->getIsPublished()
      ];
    })->toArray();
  }

  public function getRakutenLinkExpired($hasDeletedAt = true)
  {
    $adDetails = $this->adDetailRepository->findRakutenLinkExpired($hasDeletedAt);
    return collect($adDetails)->map(function ($adDetail) {
      return (object)[
        'id' => $adDetail->getId()->getId(),
        'itemName' => $adDetail->getItemName(),
        'imageUrl' => $this->getImageUrl($adDetail),
        'officialItemLink' => $adDetail->getOfficialItemLink(),
        'rakutenId' => $adDetail->getRakutenId(),
        'rakutenExpiredAt' => $adDetail->getRakutenExpiredAt(),
        'deletedAt' => $adDetail->getDeletedAt()
      ];
    })->toArray();
  }

  public function getDeletedItems()
  {
    $adDetails = $this->adDetailRepository->findDeletedItems();
    return collect($adDetails)->map(function ($adDetail) {
      return (object)[
        'id' => $adDetail->getId()->getId(),
        'itemName' => $adDetail->getItemName(),
        'imageUrl' => $this->getImageUrl($adDetail),
        'officialItemLink' => $adDetail->getOfficialItemLink(),
        'rakutenId' => $adDetail->getRakutenId(),
        'rakutenExpiredAt' => $adDetail->getRakutenExpiredAt(),
        'deletedAt' => $adDetail->getDeletedAt()
      ];
    })->toArray();
  }

  private function getImageUrl($adDetail)
  {
    if (!!($adDetail->getDetailImg())) {
      return $adDetail->getDetailImg();
    } else {
      $ad = $this->adRepository->findById($adDetail->getAdId());
      if (!!($ad->getAffiImg())) {
        return $ad->getAffiImg();
      }
      return 'https://picsum.photos/id/11/10/6';
    }
  }
}
