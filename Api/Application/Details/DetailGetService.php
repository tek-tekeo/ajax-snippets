<?php
namespace AjaxSnippets\Api\Application\Details;

use AjaxSnippets\Api\Domain\Models\Details\Detail;
use AjaxSnippets\Api\Domain\Models\Details\IDetailRepository;
use AjaxSnippets\Api\Domain\Models\BaseEls\ParentNode;
use AjaxSnippets\Api\Domain\Models\BaseEls\IParentNodeRepository;
use AjaxSnippets\Api\Domain\Models\Asps\IAspRepository;

class DetailGetService implements IDetailGetService
{
  private $detailRepository;
  private $parentNodeRepository;

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

    return array_map(function($d){
      $parent = $this->parentNodeRepository->ParentFindById($d->parent()->id());
      $d->setParent($parent);
      $asp = $this->aspRepository->AspFindByName($d->parent()->aspName());
      $d->setAsp($asp);
      return new EditDetailData($d);
    }, $details);
  }
}


class DetailData
{
  public function __construct(Detail $detail)
  {
    $this->id = $detail->id();
    $this->parent->id = $detail->parent()->id();
    $this->parent->name = $detail->parent()->name();
    $this->itemName = $detail->itemName();
    $this->officialItemLink = $detail->officialItemLink();
    $this->affiItemLink = $detail->affiItemLink();
    $this->detailImg = $detail->detailImg();
    $this->amazonAsin = $detail->amazonAsin();
    $this->rakutenId = $detail->rakutenId();
    $this->rchart = json_decode($detail->rchart()); //json_decode
    $this->info = json_decode($detail->info()); //json_decode
    $this->isShowUrl = (bool)$detail->isShowUrl();
    $this->sameParent = (bool)$detail->sameParent();
    $this->review = $detail->review();
  }
}

class EditDetailData
{
  public function __construct(Detail $detail)
  {
    $this->id = $detail->id();
    $this->name = $detail->parent()->name() . ' ' . $detail->itemName();
    $this->officialItemLink = $detail->officialItemLink();
    $this->affiLink = $detail->getRedirectUrl();
    $this->aspName = $detail->getAsp()->getAspName();
    $this->amazonAsin = $detail->amazonAsin();
    $this->rakutenId = $detail->rakutenId();
  }
  
}