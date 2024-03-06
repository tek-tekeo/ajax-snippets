<?php
namespace AjaxSnippets\Api\Domain\Models\Log;

class LogId
{
  public function __construct(
    private int $id = 0
  ){}

  public function getId(): int
  {
    return $this->id;
  }
}