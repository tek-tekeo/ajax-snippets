<?php
namespace AjaxSnippets\Api\Application\TagLinks;

use AjaxSnippets\Api\Controllers\TagRankingCommand;
use AjaxSnippets\Api\Domain\Models\TagLinks\TagLink;
use AjaxSnippets\Api\Domain\Models\TagLinks\ITagLinkRepository;
use AjaxSnippets\Api\Domain\Models\Details\Detail;
use AjaxSnippets\Api\Domain\Models\Details\IDetailRepository;
use AjaxSnippets\Api\Domain\Models\Tags\Tag;

class TagLinkAppService
{
  private $tagLinkRepository;
  private $detailRepository;

  public function __construct(ITagLinkRepository $tagLinkRepository, IDetailRepository $detailRepository)
  {
    $this->tagLinkRepository = $tagLinkRepository;
    $this->detailRepository = $detailRepository;
  }

  public function create(TagLinkCreateCommand $cmd):bool
  {
    $detail = $this->detailRepository->DetailFindById($cmd->getItemId());
    $tag = new TagLink(
      $cmd->getId(),
      $detail,
      new Tag($cmd->getTagId())
    );
    $res = $this->tagLinkRepository->save($tag);
    if($res == null){
      return false;
    }
    return true; 
  }

  public function update(TagLinkUpdateCommand $cmd):bool
  {
    $detail = $this->detailRepository->DetailFindById($cmd->getItemId());
    $tag = new TagLink(
      $cmd->getId(),
      $detail,
      new Tag($cmd->getTagId())
    );
    $res = $this->tagLinkRepository->save($tag);
    if($res == null){
      return false;
    }
    return true; 
  }

  public function get(TagLinkGetCommand $cmd)
  {
    $tags = $this->tagLinkRepository->get($cmd->getItemId());
    if($tags == null){
      return null;
    }
    return array_map(function($tag){
      return new TagLinkData($tag);
    },$tags);
  }

  public function delete(TagLinkDeleteCommand $cmd)
  {
    $tag = $this->tagLinkRepository->delete($cmd->getItemId());
  }

  public function createTagRanking(TagRankingCommand $cmd)
  {
    $itemIds = $this->tagLinkRepository->getItemIdsByTag($cmd->getTagId());

    //ランキング分析
    $disp_array = array();
    $sum_values = array();
    $names = array();
    foreach($itemIds as $itemId){
      $detail = $this->detailRepository->DetailFindById($itemId);
      $values = json_decode($detail->rchart(), true);

      $sum = 0;

      foreach((array)$values as $v){
        $sum = $sum + $v['value'];
      }

      array_push($disp_array, array('itemId'=>$itemId, 'name'=>$detail->itemName()));
      array_push($sum_values, $sum);
    }
    array_multisort($sum_values, SORT_DESC, $disp_array);

    return $disp_array;
  }

}

class TagLinkData
{
  public function __construct(TagLink $tag)
  {
    $this->id = $tag->getId();
    $this->itemId = $tag->getItemId();
    $this->tagId = $tag->getTagId();
  }
}

class TagLinkCreateCommand
{
  public function __construct(int $itemId, int $tagId)
  {
    $this->id = 0;
    $this->itemId = $itemId;
    $this->tagId = $tagId;
  }

  public function getId()
  {
    return $this->id;
  }

  public function getItemId()
  {
    return $this->itemId;
  }

  public function getTagId()
  {
    return $this->tagId;
  }
}

class TagLinkUpdateCommand
{
  public function __construct(int $id, int $itemId, int $tagId)
  {
    $this->id = $id;
    $this->itemId = $itemId;
    $this->tagId = $tagId;
  }

  public function getId()
  {
    return $this->id;
  }

  public function getItemId()
  {
    return $this->itemId;
  }

  public function getTagId()
  {
    return $this->tagId;
  }
}

class TagLinkGetCommand
{
  public function __construct(int $itemId)
  {
    $this->itemId = $itemId;
  }

  public function getItemId()
  {
    return $this->itemId;
  }
}

class TagLinkDeleteCommand
{
  public function __construct(int $itemId)
  {
    $this->itemId = $itemId;
  }

  public function getItemId()
  {
    return $this->itemId;
  }
}