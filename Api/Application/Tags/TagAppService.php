<?php
namespace AjaxSnippets\Api\Application\Tags;

use AjaxSnippets\Api\Domain\Models\Tags\Tag;
use AjaxSnippets\Api\Domain\Models\Tags\ITagRepository;

class TagAppService
{
  private $tagRepository;

  public function __construct(ITagRepository $tagRepository)
  {
    $this->tagRepository = $tagRepository;
  }

  public function getAll():array
  {
    $res = $this->tagRepository->getAllTags();
    return array_map(function($r){
      return new TagData($r);
    },$res);
  }

  public function create(TagCreateCommand $cmd):bool
  {
    $tag = new Tag(
      $cmd->getId(),
      $cmd->getTagName(),
      $cmd->getTagOrder(),
    );
    $res = $this->tagRepository->save($tag);
    if($res == null){
      return false;
    }
    return true; 
  }

  public function update(TagUpdateCommand $cmd):bool
  {
    $tag = new Tag(
      $cmd->getId(),
      $cmd->getTagName(),
      $cmd->getTagOrder(),
    );
    $res = $this->tagRepository->save($tag);
    if($res == null){
      return false;
    }
    return true; 
  }

  public function get(TagGetCommand $cmd)
  {
    $tag = $this->tagRepository->get($cmd->getId());
    if($tag == null){
      return null;
    }
    return new TagData($tag); 
  }

  public function delete(TagDeleteCommand $cmd)
  {
    $tag = $this->tagRepository->delete($cmd->id);
  }

  public function search(string $name)
  {
    $tags = $this->tagRepository->getTagsByName($name);
    return array_map(function($t){
      return new TagData($t);
    },$tags);
  }

}

class TagData
{
  public function __construct(Tag $tag)
  {
    $this->id = $tag->getId();
    $this->tagName = $tag->getTagName();
    $this->tagOrder = $tag->getTagOrder();
  }
}

class TagCreateCommand
{
  public function __construct(string $tagName, int $tagOrder)
  {
    $this->id = 0;
    $this->tagName = $tagName;
    $this->tagOrder = $tagOrder;
  }

  public function getId()
  {
    return $this->id;
  }

  public function getTagName()
  {
    return $this->tagName;
  }

  public function getTagOrder()
  {
    return $this->tagOrder;
  }
}

class TagUpdateCommand
{
  public function __construct(int $id, string $tagName, int $tagOrder)
  {
    $this->id = $id;
    $this->tagName = $tagName;
    $this->tagOrder = $tagOrder;
  }

  public function getId()
  {
    return $this->id;
  }

  public function getTagName()
  {
    return $this->tagName;
  }

  public function getTagOrder()
  {
    return $this->tagOrder;
  }
}

class TagGetCommand
{
  public function __construct(int $id)
  {
    $this->id = $id;
  }

  public function getId()
  {
    return $this->id;
  }
}

class TagDeleteCommand
{
  public function __construct(int $id)
  {
    $this->id = $id;
  }

  public function getId()
  {
    return $this->id;
  }
}