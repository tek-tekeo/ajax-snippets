<?php
namespace AjaxSnippets\Application\AppServices\Asp;

interface IAspUpdateService
{
  public function handle(AspUpdateCommand $cmd);
}

class AspUpdateCommand
{
  public function __construct(
    int $id,
    string $aspName,
    string $connectString
  )
  {
    $this->id = $id;
    $this->aspName = $aspName;
    $this->connectString = $connectString;
  }

  public function id(){
    return $this->id;
  }

  public function aspName(){
    return $this->aspName;
  }

  public function connectString(){
    return $this->connectString;
  }
}
