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
    private string $rchart = '[]',
    private string $info = '[]',
    private string $review = '',
    private int $isShowUrl = 0,
    private int $sameParent = 1
  ){}

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
      'rchart' => $this->rchart,
      'info' => $this->info,
      'review' => $this->review,
      'is_show_url' => $this->isShowUrl,
      'same_parent' => $this->sameParent
    ];
  }
}

?>