<?php
namespace AjaxSnippets\Api\Application\App;

use AjaxSnippets\Api\Application\DTO\AppData;

interface IAppGetService
{
  public function handle(AppGetCommand $cmd): AppData;
  public function getAll();
}

class AppGetCommand
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