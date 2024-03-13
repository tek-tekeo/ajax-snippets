<?php
namespace AjaxSnippets\Api\Application\TagLink;

use AjaxSnippets\Api\Domain\Models\TagLink\TagLinkId;

interface ITagLinkCreateService
{
  public function handle(TagLinkCreateCommand $cmd): TagLinkId;
}

class TagLinkCreateCommand
{
  private int $adDetailId;
  private int $tagId;

  public function __construct(\WP_REST_Request $req)
  {
    $this->adDetailId = (int)$req->get_param('adDetailId');
    $this->tagId = (int)$req->get_param('tagId');
  }

  public function getAdDetailId(): int
  {
    return $this->adDetailId;
  }

  public function getTagId(): int
  {
    return $this->tagId;
  }
}