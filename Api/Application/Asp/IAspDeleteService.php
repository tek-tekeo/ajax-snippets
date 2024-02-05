<?php
namespace AjaxSnippets\Api\Application\Asp;

interface IAspDeleteService
{
  public function handle(AspDeleteCommand $command):bool;
}

class AspDeleteCommand
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