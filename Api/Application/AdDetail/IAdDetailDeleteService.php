<?php
namespace AjaxSnippets\Api\Application\AdDetail;

interface IAdDetailDeleteService
{
  public function handle(AdDetailDeleteCommand $cmd): bool;
}

class AdDetailDeleteCommand
{
  private int $id;
  public function __construct(\WP_REST_Request $req)
  {
    $this->id = (int)$req->get_param('id');
  }

  public function getId(): int
  {
    return $this->id;
  }
}