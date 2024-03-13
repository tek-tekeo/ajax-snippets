<?php
namespace AjaxSnippets\Api\Application\DTO\Ad;

use AjaxSnippets\Api\Domain\Models\Ad\Ad;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;

class AdDetailDataIndex
{
  public int $id;
  public string $name;

  public function __construct(
    private Ad $ad,
    private AdDetail $adDetail
  ){
    $this->id = $adDetail->getId()->getId();
    $this->name = $ad->getName() .' '. $adDetail->getItemName();
  }

}