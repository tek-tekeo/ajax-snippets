<?php

namespace AjaxSnippets\Api\Domain\Models\AdDetail;

use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\Ad\AdId;

class AdDetail
{
  public function __construct(
    private AdDetailId $id,
    private AdId $adId,
    private string $itemName = '',
    private string $officialItemLink = '',
    private string $affiItemLink = '',
    private string $detailImg = '',
    private string $amazonAsin = '',
    private string $rakutenId = '',
    private string $review = '',
    private int $isShowUrl = 0,
    private int $sameParent = 1,
    private ?string $rakutenExpiredAt = null,
    private string $createdAt = '',
    private string $updatedAt = '',
    private ?string $deletedAt = null
  ) {
    $this->createdAt = $this->createdAt ?: date('Y-m-d H:i:s');
    $this->updatedAt = $this->updatedAt ?: date('Y-m-d H:i:s');
  }

  public function getId(): AdDetailId
  {
    return $this->id;
  }

  public function getAdId(): AdId
  {
    return $this->adId;
  }

  public function getItemName(): string
  {
    return $this->itemName;
  }

  public function getOfficialItemLink(): string
  {
    return $this->officialItemLink;
  }

  public function getAffiItemLink(): string
  {
    return $this->affiItemLink;
  }

  public function getDetailImg(): string
  {
    return $this->detailImg;
  }

  public function getAmazonAsin(): string
  {
    return $this->amazonAsin;
  }

  public function getRakutenId(): string
  {
    return $this->rakutenId;
  }

  public function getReview(): string
  {
    return $this->review;
  }

  public function getIsShowUrl(): int
  {
    return $this->isShowUrl;
  }

  public function getSameParent(): int
  {
    return $this->sameParent;
  }

  public function getRakutenExpiredAt(): ?string
  {
    return $this->rakutenExpiredAt;
  }

  public function getCreatedAt(): string
  {
    return $this->createdAt;
  }

  public function getUpdatedAt(): string
  {
    return $this->updatedAt;
  }

  public function getDeletedAt(): ?string
  {
    return $this->deletedAt;
  }

  public function entity(): array
  {
    return [
      'id' => $this->id->getId(),
      'ad_id' => $this->adId->getId(),
      'item_name' => $this->itemName,
      'official_item_link' => $this->officialItemLink,
      'affi_item_link' => $this->affiItemLink,
      'detail_img' => $this->detailImg,
      'amazon_asin' => $this->amazonAsin,
      'rakuten_id' => $this->rakutenId,
      'review' => $this->review,
      'is_show_url' => $this->isShowUrl,
      'same_parent' => $this->sameParent,
      'rakuten_expired_at' => $this->rakutenExpiredAt,
      'created_at' => $this->createdAt,
      'updated_at' => $this->updatedAt,
      'deleted_at' => $this->deletedAt
    ];
  }
}
