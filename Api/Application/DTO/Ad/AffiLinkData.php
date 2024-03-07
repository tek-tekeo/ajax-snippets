<?php
namespace AjaxSnippets\Api\Application\DTO\Ad;

use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;
use AjaxSnippets\Api\Domain\Models\Ad\Ad;

class AffiLinkData
{
  public $itemId;
  public $content;
  public $url;
  public $officialItemLink;
  public $imgSrc;
  public $imgAlt;
  public $imgWidth;
  public $imgHeight;
  public $imgTag;
  public $isShowUrl;
  public $sameParent;
  public $place;
  public $rakutenId;
  public $name;

  public function __construct(Ad $ad, AdDetail $detail)
  {
    $this->itemId = $detail->getId();
    $this->content = $detail->getOfficialItemLink();
    // $this->url = $detail->getRedirectUrl();
    $this->officialItemLink = $detail->getOfficialItemLink();
    // if($detail->getSameParent() == 1){
    //   $this->imgSrc = $ad->getAffiImg();
    //   $this->imgAlt = $ad->getName();
    //   $this->imgWidth = $ad->getAffiImgWidth();
    //   $this->imgHeight = $ad->getAffiImgHeight();
    // }else{
    //   $this->imgSrc = $detail->getDetailImg();
    //   $this->imgAlt = $ad->name() . ' ' . $detail->getItemName();
    //   if($this->imgSrc != null){
    //     preg_match('/wp-content.+/', $detail->getDetailImg(), $matches, PREG_OFFSET_CAPTURE);
    //     $size = getimagesize('./'.$matches[0][0]);
    //     $ratio = $size[1]/$size[0];
    //     $this->imgWidth = 300;
    //     $this->imgHeight = (int)300*$ratio;
    //   }
    // }
    $this->imgTag = $ad->getImgTag();
    $this->place = 'place';

    //以下、rakutenの商品リンクのみ使用
    $this->rakutenId = $detail->getRakutenId();
    $this->name = $ad->getName();
  }
}