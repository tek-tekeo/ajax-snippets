<?php
namespace AjaxSnippets\Api\Application\Asp;

interface IAspUpdateService
{
  public function handle(AspUpdateCommand $cmd);
}

class AspUpdateCommand
{
  private int $id;
  private string $aspName;
  private string $connectString;

  public function __construct(\WP_REST_Request $req)
  {
    $this->id = $req->get_param('id');
    $this->aspName = $req->get_param('aspName');
    $this->connectString = $req->get_param('connectString');
  }

  public function getId(){
    return $this->id;
  }

  public function getAspName(){
    return $this->aspName;
  }

  public function getConnectString(){
    return $this->connectString;
  }
}
