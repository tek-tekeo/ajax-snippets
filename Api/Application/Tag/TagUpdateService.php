<?php
namespace AjaxSnippets\Api\Application\Tag;

use AjaxSnippets\Api\Application\Tag\ITagUpdateService;
use AjaxSnippets\Api\Application\Tag\TagUpdateCommand;
use AjaxSnippets\Api\Domain\Models\Tag\TagId;
use AjaxSnippets\Api\Domain\Models\Tag\Tag;
use AjaxSnippets\Api\Domain\Models\Tag\ITagRepository;

class TagUpdateService implements ITagUpdateService
{
  public function __construct(private ITagRepository $tagRepository)
  {}

  public function handle(TagUpdateCommand $cmd)
  {
    $tag = new Tag(
      new TagId($cmd->getId()),
      $cmd->getTagName(),
      $cmd->getTagOrder(),
    );
    return $this->tagRepository->save($tag);
  }
}