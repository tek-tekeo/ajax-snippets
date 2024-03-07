<?php
namespace AjaxSnippets\Api\Application\AdDetail;

use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;

interface IAdDetailCreateService
{
  public function handle(AdDetailCreateCommand $cmd): AdDetailId;
}

class AdDetailCreateCommand
{
  private int $itemId;
  private int $adId;
  private string $itemName;
  private string $officialItemLink;
  private string $affiItemLink;
  private string $detailImg;
  private string $amazonAsin;
  private string $rakutenId;
  private string $rchart;
  private string $info;
  private string $review;
  private int $isShowUrl;
  private int $sameParent;

  public function __construct(\WP_REST_Request $req)
  {
    $this->itemId = (int)$req->get_param('itemId');
    $this->adId = (int)$req->get_param('id');
    $this->itemName = (string)$req->get_param('itemName');
    $this->officialItemLink = (string)$req->get_param('officialItemLink');
    $this->affiItemLink = (string)$req->get_param('affiItemLink');
    $this->detailImg = (string)$req->get_param('detailImg');
    $this->amazonAsin = (string)$req->get_param('amazonAsin');
    $this->rakutenId = (string)$req->get_param('rakutenId');
    $this->rchart = (string)$req->get_param('rchart');
    $this->info = (string)$req->get_param('info');
    $this->review = (string)$req->get_param('review');
    $this->isShowUrl = (int)$req->get_param('isShowUrl');
    $this->sameParent = (int)$req->get_param('sameParent');
  }

  public function getItemId(): int
  {
    return $this->itemId;
  }

  public function getAdId(): int
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

  public function getRchart(): string
  {
    return $this->rchart;
  }

  public function getInfo(): string
  {
    return $this->info;
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
}