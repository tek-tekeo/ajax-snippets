<?php
namespace AjaxSnippets\Api\Application\Ad;

interface IAdDeleteService
{
  public function handle(AdDeleteCommand $cmd): bool;
}

class AdDeleteCommand
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