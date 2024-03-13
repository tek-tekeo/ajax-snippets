<?php
namespace AjaxSnippets\Api\Application\TagLink;

use AjaxSnippets\Api\Domain\Models\TagLink\TagLinkId;

interface ITagLinkGetService
{
  public function handle(TagLinkGetCommand $cmd): array;
}

class TagLinkGetCommand
{
  private int $adDetailId;

  public function __construct(\WP_REST_Request $req)
  {
    $this->adDetailId = (int)$req->get_param('adDetailId');
  }

  public function getAdDetailId(): int
  {
    return $this->adDetailId;
  }

}