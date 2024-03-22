<?php
namespace AjaxSnippets\Api\Application\TagLink;

use AjaxSnippets\Api\Domain\Models\TagLink\TagLinkId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\TagLink\ITagLinkRepository;

class TagLinkDeleteService implements ITagLinkDeleteService
{
  public function __construct(private ITagLinkRepository $tagLinkRepository)
  {
    $this->tagLinkRepository = $tagLinkRepository;
  }

  public function handle(TagLinkDeleteCommand $cmd): bool
  {
    return $this->tagLinkRepository->delete(new adDetailId($cmd->getAdDetailId()));
  }
}