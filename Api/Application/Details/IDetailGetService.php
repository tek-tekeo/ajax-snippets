<?php
namespace AjaxSnippets\Api\Application\Details;

use AjaxSnippets\Api\Domain\Models\Details\ParentNode;
use AjaxSnippets\Api\Domain\Models\Details\Detail;


interface IDetailGetService
{
  public function handle(DetailGetCommand $cmd);
  public function getLinkMaker(DetailGetCommand $cmd);
  public function getDetailsFindByName(string $name);
  public function getEditorAnkenList(string $name);
}

class DetailGetCommand
{
  private int $id;

  public function __construct(int $id)
  {
    $this->id = $id;
  }

  public function id()
  {
    return $this->id;
  }
}