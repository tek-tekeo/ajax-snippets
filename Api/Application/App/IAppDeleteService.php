<?php
namespace AjaxSnippets\Api\Application\App;

interface IAppDeleteService
{
  public function handle(AppDeleteCommand $command):bool;
}

class AppDeleteCommand
{
  private int $id;
  
  public function __construct(\WP_REST_Request $req)
  {
    $this->id = $req->get_param('id');
  }

  public function getId(){
    return $this->id;
  }
}