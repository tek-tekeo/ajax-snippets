<?php
namespace AjaxSnippets\Api\Application\Asp;

interface IAspCreateService
{
  public function handle(AspCreateCommand $cmd);
}

class AspCreateCommand
{
  private string $aspName;
  private string $connectString;

  public function __construct(\WP_REST_Request $req)
  {
    $this->aspName = $req->get_param('aspName');
    $this->connectString = $req->get_param('connectString');
  }

  public function getAspName(){
    return $this->aspName;
  }

  public function getConnectString(){
    return $this->connectString;
  }
}
