<?php
namespace AjaxSnippets\Domain\Models;

use AjaxSnippets\Domain\Models\BaseModel;

class Detail extends BaseModel
{
  const TABLE_NAME = "detail";

  protected $casts = [
    'id' => 'int',
    'base_id' => 'int'
  ];
  protected $columns = [
    'id',
    'base_id',
    'item_name',
    'official_item_link',
    'affi_item_link',
    'amazon_asin',
    'rakuten_id',
    'rchart',
    'info',
    'review'
  ];
}
