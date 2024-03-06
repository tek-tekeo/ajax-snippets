<?php
namespace AjaxSnippets\Api\Application\Log;

interface ILogCreateService
{
  public function handle(LogCreateCommand $cmd);
}

class LogCreateCommand
{
  private int $itemId;
  private string $place;

  public function __construct(\WP_REST_Request $req)
  {
    $this->itemId = (int)$req->get_param('itemId');
    $this->place = (string)$req->get_param('place');
  }

  public function getItemId()
  {
    return $this->itemId;
  }

  public function getPlace()
  {
    return $this->place;
  }

}