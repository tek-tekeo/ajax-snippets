<?php
namespace AjaxSnippets\Api\Application\AdDetail;

use AjaxSnippets\Api\Domain\Models\Ad\Ad;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetail;

interface IAdDetailGetService
{
  public function handle(AdDetailGetCommand $cmd);
  public function getLinkMaker(AdDetailGetCommand $cmd);
  public function getAdDetailsFindByName(string $name);
  public function getEditorAnkenList(string $name);
}

class AdDetailGetCommand
{
  private int $id;

  public function __construct(\WP_REST_Request $req)
  {
    $this->id = $req->get_param('id');
  }

  public function getId()
  {
    return $this->id;
  }
}