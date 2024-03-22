<?php
namespace AjaxSnippets\Api\Domain\Models\Tag;

use AjaxSnippets\Api\Domain\Models\Tag\TagId;

class Tag
{
  private $name;
  private $order;

  public function __construct(
    private TagId $id,
    string $name = null,
    int $order = 0
  )
  {
    $this->id = $id;
    $this->name = $name;
    $this->order = $order;
  }

  public function getId()
  {
    return $this->id;
  }

  public function getName()
  {
    return $this->name;
  }

  public function getOrder()
  {
    return $this->order;
  }

  public function entity()
  {
    return [
      'id' => $this->id->getId(),
      'tag_name' => $this->name,
      'tag_order' => $this->order
    ];
  }
}

?>