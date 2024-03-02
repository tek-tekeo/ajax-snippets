<?php
namespace AjaxSnippets\Api\Application\Tag;

use AjaxSnippets\Api\Application\Tag\ITagDeleteService;
use AjaxSnippets\Api\Application\Tag\TagDeleteCommand;
use AjaxSnippets\Api\Domain\Models\Tag\Tag;
use AjaxSnippets\Api\Domain\Models\Tag\TagId;
use AjaxSnippets\Api\Domain\Models\Tag\ITagRepository;

class TagDeleteService implements ITagDeleteService
{
  private $tagRepository;

  public function __construct(ITagRepository $tagRepository)
  {
    $this->tagRepository = $tagRepository;
  }

  public function handle(TagDeleteCommand $cmd)
  {
    $tagId = new TagId($cmd->getId());
    $res = $this->tagRepository->delete($tagId);
    return $res;
  }
}