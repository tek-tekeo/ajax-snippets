<?php
namespace AjaxSnippets\Application\AppServices\Asp;

interface IAspCreateService
{
  public function handle(AspCreateCommand $cmd);
}

class AspCreateCommand
{
  public function __construct(
    string $aspName,
    string $connectString
  )
  {
    $this->aspName = $aspName;
    $this->connectString = $connectString;
  }

  public function aspName(){
    return $this->aspName;
  }

  public function connectString(){
    return $this->connectString;
  }
}
