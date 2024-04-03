<?php
namespace AjaxSnippets\Api\Application\TagLink;

use AjaxSnippets\Api\Domain\Models\TagLink\TagLink;
use AjaxSnippets\Api\Domain\Models\TagLink\TagLinkId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\Tag\TagId;
use AjaxSnippets\Api\Domain\Models\TagLink\ITagLinkRepository;
use AjaxSnippets\Api\Application\TagLink\TagLinkCreateCommand;

class TagLinkCreateService implements ITagLinkCreateService
{

  public function __construct(
    private ITagLinkRepository $tagLinkRepository)
  {}

  public function handle(TagLinkCreateCommand $cmd): TagLinkId
  {
    $tagLink = new TagLink(
      new TagLinkId(),
      new AdDetailId($cmd->getAdDetailId()),
      new TagId($cmd->getTagId())
    );
    return $this->tagLinkRepository->save($tagLink);
  }
}