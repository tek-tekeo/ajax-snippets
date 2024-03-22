<?php
namespace AjaxSnippets\Api\Application\TagLink;

use AjaxSnippets\Api\Domain\Models\TagLink\TagLink;
use AjaxSnippets\Api\Domain\Models\TagLink\TagLinkId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\Tag\TagId;
use AjaxSnippets\Api\Domain\Models\TagLink\ITagLinkRepository;
use AjaxSnippets\Api\Application\TagLink\TagLinkUpdateCommand;

class TagLinkUpdateService implements ITagLinkUpdateService
{
  public function __construct(private ITagLinkRepository $tagLinkRepository)
  {
    $this->tagLinkRepository = $tagLinkRepository;
  }

  public function handle(TagLinkUpdateCommand $cmd): array
  {
    $this->tagLinkRepository->delete(new AdDetailId($cmd->getAdDetailId()));
    return collect($cmd->getTagIds())->map(function($tagId) use ($cmd){
      $tagLink = new TagLink(
        new TagLinkId(),
        new AdDetailId($cmd->getAdDetailId()),
        new TagId($tagId)
      );
      return $this->tagLinkRepository->update($tagLink);
    })->toArray();
  }
}