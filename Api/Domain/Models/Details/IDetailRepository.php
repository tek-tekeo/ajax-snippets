<?php
namespace AjaxSnippets\Api\Domain\Models\Details;

use AjaxSnippets\Api\Domain\Models\Details\Detail;

interface IDetailRepository
{
  public function DetailFindById(int $detailId);
  public function DetailFindByName(string $name);
  public function saveDetail(Detail $detail) : int;
}