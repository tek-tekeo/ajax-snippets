<?php
namespace AjaxSnippets\Api\Application\Tag;

use AjaxSnippets\Api\Application\Tag\ITagCreateService;
use AjaxSnippets\Api\Application\Tag\TagCreateCommand;
use AjaxSnippets\Api\Domain\Models\Tag\Tag;
use AjaxSnippets\Api\Domain\Models\Tag\TagId;
use AjaxSnippets\Api\Domain\Models\Tag\ITagRepository;

class TagCreateService implements ITagCreateService
{
  private $tagRepository;

  public function __construct(ITagRepository $tagRepository)
  {
    $this->tagRepository = $tagRepository;
  }

  public function handle(TagCreateCommand $cmd)
  {
    $tag = new Tag(
      new TagId(),
      $cmd->getTagName(),
      $cmd->getTagOrder(),
    );
    return $this->tagRepository->save($tag); 
  }
}