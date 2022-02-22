<?php
namespace AjaxSnippets\Api\Domain\Models\Details;

use AjaxSnippets\Api\Domain\Models\BaseEls\ParentNode;
use AjaxSnippets\Api\Domain\Models\Asps\Asp;

class Detail
{  													
  private int $id;
  private ParentNode $parent;
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
  private Asp $asp;

  public function __construct(
    int $id,
    ParentNode $parent,
    string $itemName = null,
    string $officialItemLink = null,
    string $affiItemLink = null,
    string $detailImg = null,
    string $amazonAsin = null,
    string $rakutenId = null,
    string $rchart = null,
    string $info = null,
    string $review = null,
    int $isShowUrl,
    int $sameParent
  )
  {
    if($id === null){
      $this->id = 0; //0を設定していると自動でauto incrementしてくれる
    }else{
      $this->id = $id;
    }
    $this->parent = $parent;
    $this->itemName =$itemName;
    $this->officialItemLink=$officialItemLink;
    $this->affiItemLink =$affiItemLink;
    $this->detailImg = $detailImg;
    $this->amazonAsin = $amazonAsin;
    $this->rakutenId = $rakutenId;
    $this->rchart = $rchart;
    $this->info = $info;
    $this->review = $review;
    $this->isShowUrl = $isShowUrl;
    $this->sameParent = $sameParent;
  }

  //以下、ドメインの知識のみ
  public function id():int
  {
    return $this->id;
  }

  public function parent():ParentNode
  {
    return $this->parent;
  }

  public function itemName():string
  {
    return $this->itemName;
  }

  public function officialItemLink():string
  {
    return $this->officialItemLink;
  }
  
  public function affiItemLink():string
  {
    return $this->affiItemLink;
  }
  
  public function detailImg():string
  {
    return $this->detailImg;
  }
  
  public function amazonAsin():string
  {
    return $this->amazonAsin;
  }
  
  public function rakutenId():string
  {
    return $this->rakutenId;
  }

  public function rchart():string
  {
    return $this->rchart;
  }
  
  public function info():string
  {
    return $this->info;
  }

  public function review():string
  {
    return $this->review;
  }

  public function getWpReview(): string
  {
    return wpautop($this->review);
  }

  public function getWpInfo()
  {
    return $this->changeWp($this->info);
  }

  private function changeWp($data)
  {
    $els = json_decode($data);
    $newEls = array_map(function($e){
      return array(
        "factor" => $e->factor,
        "value" => wpautop($e->value),
      );
    },$els);

    return $newEls;
  }

  public function isShowUrl():int
  {
    return $this->isShowUrl;
  }

  public function sameParent():int
  {
    return $this->sameParent;
  }

  public function getAsp():Asp
  {
    return $this->asp;
  }

  public function getRedirectUrl():string
  {
    if($this->sameParent() == true || $this->getAsp() == null){
      $url = $this->parent()->affiLink();
    }else if($this->parent()->aspName() != 'a8'){
      //a8以外の場合
      $url = $this->affiItemLink();
    }else{
      $url = $this->parent()->sLink() . $this->getAsp()->getConnectString() . urlencode($this->officialItemLink);
    }

    return $url;
  }

  public function getDirectUrl(): string
  {
    return home_url() . "/".$this->parent()->anken() . "?no=".$this->id."&pl=PLACE_ID";
  }

  public function setAsp(Asp $asp)
  {
    $this->asp = $asp;
  }
  public function setId(int $appId)
  {
    //インクリメントなので重複チェックは不要
  }
  public function setParent(ParentNode $parent)
  {
    $this->parent = $parent;
  }
  public function setItemName(string $itemName)
  {
    $this->itemName = $itemName;
  }

  public function setOfficialItemLink(string $officialItemLink)
  {
    $this->officialItemLink = $officialItemLink;
  }

  public function setAffiItemLink(string $affiItemLink)
  {
    $this->affiItemLink = $affiItemLink;
  }

  public function setDetailImg(string $detailImg)
  {
    $this->detailImg = $detailImg;
  }

  public function setAmazonAsin(string $amazonAsin)
  {
    $this->amazonAsin = $amazonAsin;
  }
  
  public function setRakutenId(string $rakutenId)
  {
    $this->rakutenId = $rakutenId;
  }
  public function setRchart(string $rchart)
  {
    $this->rchart = $rchart;
  }
  public function setInfo(string $info)
  {
    $this->info = $info;
  }

  public function setReview(string $review)
  {
    $this->review = $review;
  }

  public function setIsShowUrl(int $isShowUrl)
  {
    $this->isShowUrl = $isShowUrl;
  }
  public function setSameParent(int $sameParent)
  {
    $this->sameParent = $sameParent;
  }
}

?>