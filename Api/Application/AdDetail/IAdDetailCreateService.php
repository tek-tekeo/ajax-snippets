<?php
namespace AjaxSnippets\Api\Application\AdDetail;

use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;

interface IAdDetailCreateService
{
  public function handle(AdDetailCreateCommand $cmd): AdDetailId;
  public function handleReview(AdDetailReviewCreateCommand $cmd): int;
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
  private array $rchart;
  private array $info;
  private string $review;
  private int $isShowUrl;
  private int $sameParent;
  private array $tagIds;

  public function __construct(\WP_REST_Request $req)
  {
    $this->itemId = (int)$req->get_param('itemId');
    $this->adId = (int)$req->get_param('adId');
    $this->itemName = (string)$req->get_param('itemName');
    $this->officialItemLink = (string)$req->get_param('officialItemLink');
    $this->affiItemLink = (string)$req->get_param('affiItemLink');
    $this->detailImg = (string)$req->get_param('detailImg');
    $this->amazonAsin = (string)$req->get_param('amazonAsin');
    $this->rakutenId = (string)$req->get_param('rakutenId');
    $this->rchart = (array)$req->get_param('rchart');
    $this->info = (array)$req->get_param('info');
    $this->review = (string)$req->get_param('review');
    $this->isShowUrl = (int)$req->get_param('isShowUrl');
    $this->sameParent = (int)$req->get_param('sameParent');
    $this->tagIds = (array)$req->get_param('tagIds');
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

  public function getRates(): array
  {
    return $this->rchart;
  }

  public function getInfos(): array
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

  public function getTagIds(): array
  {
    return $this->tagIds;
  }
}

class AdDetailReviewCreateCommand
{
  private int $id;
  private int $adDetailId;
  private string $name;
  private int|null $age;
  private string $sex;
  private float $ratingValue;
  private string $content;
  private string $quoteName;
  private string $quoteUrl;
  private string $createdAt;
  private string $updatedAt;

  public function __construct(\WP_REST_Request $req)
  {
    $this->id = (int)$req->get_param('id');
    $this->adDetailId = (int)$req->get_param('adDetailId');
    $this->name = $req->get_param('name');
    $this->age = $req->get_param('age');
    $this->sex = $req->get_param('sex');
    $this->ratingValue = (float)$req->get_param('ratingValue');
    $this->content = (string)$req->get_param('content');
    $this->quoteName = (string)$req->get_param('quoteName');
    $this->quoteUrl = (string)$req->get_param('quoteUrl');
    $this->createdAt = (string)$req->get_param('createdAt');
    $this->updatedAt = (string)$req->get_param('updatedAt');
  }

  public function getId(): int
  {
    return $this->id;
  }

  public function getAdDetailId(): int
  {
    return $this->adDetailId;
  }

  public function getName(): string
  {
    if($this->name == '')
    {
      return '匿名';
    }
    return $this->name;
  }

  public function getAge(): int|null
  {
    return $this->age;
  }

  public function getSex(): string
  {
    return $this->sex;
  }

  public function getRatingValue(): float
  {
    return $this->ratingValue;
  }

  public function getContent(): string
  {
    return $this->content;
  }

  public function getQuoteName(): string
  {
    return $this->quoteName;
  }

  public function getQuoteUrl(): string
  {
    return $this->quoteUrl;
  }

  public function getCreatedAt(): string
  {
    return ($this->createdAt =='') ? date('Y-m-d H:i:s') : $this->createdAt;
  }

  public function getUpdatedAt(): string
  {
    return ($this->updatedAt =='') ? date('Y-m-d H:i:s') : $this->updatedAt;
  }
}