<?php
namespace AjaxSnippets\Api\Application\Asp;

interface IAspGetService
{
  public function handle(AspGetCommand $cmd);
  public function getAll();
}

class AspGetCommand
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