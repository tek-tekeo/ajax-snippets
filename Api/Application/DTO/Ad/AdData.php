<?php
namespace AjaxSnippets\Api\Application\DTO\Ad;

use AjaxSnippets\Api\Domain\Models\Ad\Ad;

class AdData
{
  public int $id;
  public string $name;
  public string $anken;
  public string $affiLink;
  public string $sLink;
  public int $aspId;
  public string $affiImg;
  public string $imgTag;
  public string $sImgTag;
  public int $affiImgWidth;
  public int $affiImgHeight;
  public int $appId;

  public function __construct(public Ad $ad){

    $this->id = $ad->getAdId()->getId();
    $this->name = $ad->getName();
    $this->anken = $ad->getAnken();
    $this->affiLink = $ad->getAffiLink();
    $this->sLink = $ad->getSLink();
    $this->aspId = $ad->getAspId()->getId();
    $this->affiImg = $ad->getAffiImg();
    $this->imgTag = $ad->getImgTag();
    $this->sImgTag = $ad->getSImgTag();
    $this->affiImgWidth = $ad->getAffiImgWidth();
    $this->affiImgHeight = $ad->getAffiImgHeight();
    $this->appId = $ad->getAppId()->getId();
  }

}