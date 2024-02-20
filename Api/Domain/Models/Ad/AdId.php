<?php
namespace AjaxSnippets\Api\Domain\Models\Ad;

class AdId
{
  public function __construct(private int $id = 0)
  {
    $this->id = $id;
  }

  public function getId(): int
  {
    return $this->id;
  }
}