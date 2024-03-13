<?php
namespace AjaxSnippets\Api\Application\Log;

interface ILogCreateService
{
  public function handle(LogCreateCommand $cmd);
}

class LogCreateCommand
{
  private int $adDetailId;
  private string $place;

  public function __construct(\WP_REST_Request $req)
  {
    $this->adDetailId = (int)$req->get_param('adDetailId');
    $this->place = (string)$req->get_param('place');
  }

  public function getAdDetailId()
  {
    return $this->adDetailId;
  }

  public function getPlace()
  {
    return $this->place;
  }

}