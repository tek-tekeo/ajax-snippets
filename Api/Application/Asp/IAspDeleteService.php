<?php
namespace AjaxSnippets\Api\Application\Asp;

interface IAspDeleteService
{
  public function handle(AspDeleteCommand $command):bool;
}

class AspDeleteCommand
{
  public function __construct(
    int $id
  )
  {
    $this->id = $id;
  }

  public function id(){
    return $this->id;
  }
}