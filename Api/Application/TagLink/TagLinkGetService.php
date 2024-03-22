<?php
namespace AjaxSnippets\Api\Application\TagLink;

use AjaxSnippets\Api\Domain\Models\TagLink\TagLinkId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\TagLink\ITagLinkRepository;

class TagLinkGetService implements ITagLinkGetService
{
  public function __construct(private ITagLinkRepository $tagLinkRepository)
  {}

  public function handle(TagLinkGetCommand $cmd): array
  {
    return [];
    
  }
}