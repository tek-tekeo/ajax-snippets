<?php
namespace AjaxSnippets\Api\Application\DTO\Ad;

use AjaxSnippets\Api\Domain\Models\Ad\Ad;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;

class AdDetailData
{
  public int $id;
  public int $adId;
  public string $itemName;
  public string $officialItemLink;
  public string $affiItemLink;
  public string $detailImg;
  public string $amazonAsin;
  public string $rakutenId;
  public array $rchart;
  public array $info;
  public bool $isShowUrl;
  public bool $sameParent;
  public string $review;
  public string $getWpReview;
  public string $getWpInfo;
  public array $tagIds;

  public function __construct(
    private Ad $ad,
    private AdDetail $adDetail,
    private array $tagLinks = []
  ){
    $this->id = $adDetail->getId()->getId();
    $this->adId = $ad->getAdId()->getId();
    $this->itemName = $adDetail->getItemName();
    $this->officialItemLink = $adDetail->getOfficialItemLink();
    $this->affiItemLink = $adDetail->getAffiItemLink();
    $this->detailImg = $adDetail->getDetailImg();
    $this->amazonAsin = $adDetail->getAmazonAsin();
    $this->rakutenId = $adDetail->getRakutenId();
    $this->rchart = json_decode($adDetail->getRchart()) ?? [];
    $this->info = json_decode($adDetail->getInfo()) ?? [];
    $this->isShowUrl = (bool)$adDetail->getIsShowUrl();
    $this->sameParent = (bool)$adDetail->getSameParent();
    $this->review = $adDetail->getReview();
    $this->tagIds = collect($tagLinks)->map(function($tagLink){
      return $tagLink->getTagId()->getId();
    })->toArray();
    // $this->getWpReview = $adDetail->getWpReview();
    // $this->getWpInfo = $adDetail->getWpInfo();
  }

}