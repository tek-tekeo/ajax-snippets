<?php
namespace AjaxSnippets\Api\Application\Details;

use AjaxSnippets\Api\Domain\Models\Details\Detail;
use AjaxSnippets\Api\Domain\Models\Details\IDetailRepository;
use AjaxSnippets\Api\Domain\Models\BaseEls\ParentNode;
use AjaxSnippets\Api\Domain\Models\BaseEls\IParentNodeRepository;
use AjaxSnippets\Api\Domain\Models\Asp\IAspRepository;

class DetailGetService implements IDetailGetService
{
  public $detailRepository;
  public $parentNodeRepository;
  public $aspRepository;

  public function __construct(
    IDetailRepository $detailRepository,
    IParentNodeRepository $parentNodeRepository,
    IAspRepository $aspRepository
  )
  {
    $this->detailRepository = $detailRepository;
    $this->parentNodeRepository = $parentNodeRepository;
    $this->aspRepository = $aspRepository;
  }

  public function handle(DetailGetCommand $cmd)
  {
    $detail = $this->detailRepository->DetailFindById($cmd->id());
    $parent = $this->parentNodeRepository->ParentFindById($detail->parent()->id());
    $detail->setParent($parent);
    if($parent == null){
      return null;
    }
    return new DetailData($detail);
  }

  public function getDetailsFindByName(string $name){
    $details = $this->detailRepository->DetailFindByName($name);
    return array_map(function($d){
      $parent = $this->parentNodeRepository->ParentFindById($d->parent()->id());
      $d->setParent($parent);
      return new DetailData($d);
    }, $details);
  }

  public function getEditorAnkenList(string $name){
    $details = $this->detailRepository->DetailFindByName($name);

    return array_map(function($detail){
      $d = $this->newDetailClass($detail);
      return new EditDetailData($d);
    }, $details);
  }

  public function getLinkMaker($cmd){
    $detail = $this->detailRepository->DetailFindById($cmd->id());
    $d = $this->newDetailClass($detail);
    return new AffiLinkData($d);
  }

  private function newDetailClass(Detail $detail) : Detail
  {
    $parent = $this->parentNodeRepository->ParentFindById($detail->parent()->id());
    $detail->setParent($parent);
    $asp = $this->aspRepository->AspFindByName($detail->parent()->aspName());
    $detail->setAsp($asp);
    return $detail;
  }

  public function getLatestDetail(){
    $detail = $this->detailRepository->DetailFindLatest();
    $d = $this->newDetailClass($detail);
    return new DetailData($d);
  
  }

}

class ParentData{
  public $id;
  public $name;
  public $aspName;

  public function __construct($id, $name, $aspName)
  {
    $this->id = $id;
    $this->name = $name;
    $this->aspName = $aspName;
  }
}

class DetailData
{
  public $id;
  public $parent;
  public $parentId;
  public $parentName;
  public $aspName;
  public $itemName;
  public $officialItemLink;
  public $affiItemLink;
  public $detailImg;
  public $amazonAsin;
  public $rakutenId;
  public $rchart;
  public $info;
  public $isShowUrl;
  public $sameParent;
  public $review;
  public $getWpReview;
  public $getWpInfo;
  
  public function __construct(Detail $detail)
  {
    $this->id = $detail->id();
    $this->parent = new ParentData(
      $detail->parent()->id(),
      $detail->parent()->name(),
      $detail->parent()->aspName()
    );
    $this->itemName = $detail->itemName();
    $this->officialItemLink = $detail->officialItemLink();
    $this->affiItemLink = $detail->affiItemLink();
    $this->detailImg = $detail->detailImg();
    $this->amazonAsin = $detail->amazonAsin();
    $this->rakutenId = $detail->rakutenId();
    $this->rchart = json_decode($detail->rchart()) ?? []; //json_decode
    $this->info = json_decode($detail->info()) ?? []; //json_decode
    $this->isShowUrl = (bool)$detail->isShowUrl();
    $this->sameParent = (bool)$detail->sameParent();
    $this->review = $detail->review();
    $this->getWpReview = $detail->getWpReview();
    $this->getWpInfo = $detail->getWpInfo();
  }

}

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

  public function __construct(Detail $detail)
  {
    $this->id = $detail->id();
    $this->name = $detail->parent()->name() . ' ' . $detail->itemName();
    $this->officialItemLink = $detail->officialItemLink();
    $this->affiLink = $detail->getRedirectUrl();
    $this->aspName = $detail->getAsp()->getAspName();
    $this->amazonAsin = $detail->amazonAsin();
    $this->rakutenId = $detail->rakutenId();
    $this->directLink = $detail->getDirectUrl();
  }
}

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

  public function __construct(Detail $detail)
  {
    $this->itemId = $detail->id();
    $this->content = $detail->officialItemLink();
    $this->url = $detail->getRedirectUrl();
    $this->officialItemLink = $detail->officialItemLink();
    if($detail->sameParent() == 1){
      $this->imgSrc = $detail->parent()->affiImg();
      $this->imgAlt = $detail->parent()->name();
      $this->imgWidth = $detail->parent()->affiImgWidth();
      $this->imgHeight = $detail->parent()->affiImgHeight();
    }else{
      $this->imgSrc = $detail->detailImg();
      $this->imgAlt = $detail->parent()->name() . ' ' . $detail->itemName();
      if($this->imgSrc != null){
        preg_match('/wp-content.+/', $detail->detailImg(), $matches, PREG_OFFSET_CAPTURE);
        $size = getimagesize('./'.$matches[0][0]);
        $ratio = $size[1]/$size[0];
        $this->imgWidth = 300;
        $this->imgHeight = (int)300*$ratio;
      }
    }
    $this->imgTag = $detail->parent()->imgTag();
    $this->place = 'place';

    //以下、rakutenの商品リンクのみ使用
    $this->rakutenId = $detail->rakutenId();
    $this->name = $detail->parent()->name();
  }
}