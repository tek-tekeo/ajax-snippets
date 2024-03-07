<?php
namespace AjaxSnippets\Api\Application\DTO\Ad;

use AjaxSnippets\Api\Domain\Models\Ad\Ad;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;
use AjaxSnippets\Api\Domain\Models\Asp\Asp;

class EditDetailData
{
  public $id;
  public $name;
  public $officialItemLink;
  public $affiLink;
  public $aspName;
  public $amazonAsin;
  public $rakutenId;
  public $directLink;

  public function __construct(Ad $ad, AdDetail $detail, Asp $asp)
  {
    $this->id = $detail->getId();
    $this->name = $ad->getName() . ' ' . $detail->getItemName();
    $this->officialItemLink = $detail->getOfficialItemLink();
    // $this->affiLink = $detail->getRedirectUrl();
    $this->aspName = $asp->getAspName();
    $this->amazonAsin = $detail->getAmazonAsin();
    $this->rakutenId = $detail->getRakutenId();
    // $this->directLink = $detail->getDirectUrl();
  }
  
}