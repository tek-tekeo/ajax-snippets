<?php
namespace AjaxSnippets\Domain\Models;

use AjaxSnippets\Domain\Models\BaseModel;

class AffiLog extends BaseModel
{
  const TABLE_NAME = "log";

  protected $casts = [
    'id' => 'int'
  ];
  protected $columns = [
    'id',
    'item_id',
    'date',
    'time',
    'post_addr',
    'place',
    'ip'
  ];
}
