<?php
namespace AjaxSnippets\Domain\Models;

use AjaxSnippets\Domain\Models\BaseModel;

class Base extends BaseModel
{
  const TABLE_NAME = "base";

  protected $casts = [
    'id' => 'int'
  ];
  protected $columns = [
    'id',
    'name',
    'anken',
    'affi_link',
    's_link',
    'asp_name',
    'affi_img',
    'img_tag'
  ];
}
