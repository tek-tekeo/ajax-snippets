<?php
namespace AjaxSnippets\Api\Domain\Models\ValueObject;

use AjaxSnippets\Api\Domain\Models\Ad\Ad;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;

class ClickLogURL
{
  public function __construct(
    private Ad $ad,
    private AdDetail $adDetail
  ){}

  public function getUrl(): string
  {
    return home_url() . '/link/' . $this->ad->getAnken() . '?no=' . $this->adDetail->getId()->getId() . '&pl=PLACE';
  }

}