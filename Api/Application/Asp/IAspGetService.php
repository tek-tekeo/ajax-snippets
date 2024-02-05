<?php
namespace AjaxSnippets\Api\Application\Asp;

interface IAspGetService
{
  public function handle(AspGetCommand $cmd);
  public function getAll();
}

class AspGetCommand
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