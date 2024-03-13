<?php
namespace AjaxSnippets\Api\Application\TagLink;

use AjaxSnippets\Api\Domain\Models\TagLink\TagLinkId;

interface ITagLinkUpdateService
{
  public function handle(TagLinkUpdateCommand $cmd): array;
}

class TagLinkUpdateCommand
{
  private int $adDetailId;
  private array $tagIds;

  public function __construct(\WP_REST_Request $req)
  {
    $this->adDetailId = (int)$req->get_param('id');
    $this->tagIds = (array)$req->get_param('tagIds');
  }

  public function getAdDetailId(): int
  {
    return $this->adDetailId;
  }

  public function getTagIds(): array
  {
    return $this->tagIds;
  }
}