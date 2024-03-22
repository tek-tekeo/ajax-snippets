<?php
namespace AjaxSnippets\Api\Application\TagLink;

use AjaxSnippets\Api\Domain\Models\TagLink\TagLinkId;

interface ITagLinkDeleteService
{
  public function handle(TagLinkDeleteCommand $cmd): bool;
}

class TagLinkDeleteCommand
{
  private int $adDetailId;

  public function __construct(\WP_REST_Request $req)
  {
    $this->adDetailId = (int)$req->get_param('id');
  }

  public function getAdDetailId(): int
  {
    return $this->adDetailId;
  }

}