<?php
namespace AjaxSnippets\Api\Application\TagLinks;

use AjaxSnippets\Api\Domain\Models\TagLinks\TagLink;
use AjaxSnippets\Api\Domain\Models\TagLinks\ITagLinkRepository;

class TagLinkAppService
{
  private $tagLinkRepository;

  public function __construct(ITagLinkRepository $tagLinkRepository)
  {
    $this->tagLinkRepository = $tagLinkRepository;
  }

  public function getAll():array
  {
    $res = $this->tagRepository->getAllTags();
    return array_map(function($r){
      return new TagData($r);
    },$res);
  }

  public function create(TagLinkCreateCommand $cmd):bool
  {
    $tag = new TagLink(
      $cmd->getId(),
      $cmd->getItemId(),
      $cmd->getTagId(),
    );
    $res = $this->tagLinkRepository->save($tag);
    if($res == null){
      return false;
    }
    return true; 
  }

  public function update(TagLinkUpdateCommand $cmd):bool
  {
    $tag = new TagLink(
      $cmd->getId(),
      $cmd->getItemId(),
      $cmd->getTagId(),
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