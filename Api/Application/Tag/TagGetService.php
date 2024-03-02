<?php
namespace AjaxSnippets\Api\Application\Tag;

use AjaxSnippets\Api\Application\Tag\ITagGetService;
use AjaxSnippets\Api\Application\Tag\TagGetCommand;
use AjaxSnippets\Api\Domain\Models\Tag\Tag;
use AjaxSnippets\Api\Domain\Models\Tag\TagId;
use AjaxSnippets\Api\Application\DTO\TagData;
use AjaxSnippets\Api\Domain\Models\Tag\ITagRepository;

class TagGetService implements ITagGetService
{
  private $tagRepository;

  public function __construct(ITagRepository $tagRepository)
  {
    $this->tagRepository = $tagRepository;
  }

  public function handle(TagGetCommand $cmd)
  {
    $tagId = new TagId($cmd->getId());
    $res = $this->tagRepository->findById($tagId);
    return new TagData($res);
  }

  public function getAll()
  {
    $res = $this->tagRepository->findByName('');
    return array_map(function($r){
      return new TagData($r);
    },$res);
  }

  public function search(TagSearchCommand $cmd)
  {
    $res = $this->tagRepository->findByName($cmd->getSearchStr());
    return array_map(function($r){
      return new TagData($r);
    },$res);
  }
}