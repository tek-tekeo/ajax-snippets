<?php 
namespace AjaxSnippets\Api\Application\Tag;

interface ITagUpdateService
{
  public function handle(TagUpdateCommand $cmd);
}

class TagUpdateCommand
{
  private int $id;
  private string $tagName;
  private int $tagOrder;

  public function __construct(\WP_REST_Request $req)
  {
    $this->id = $req->get_param('id');
    $this->tagName = $req->get_param('tagName');
    $this->tagOrder = $req->get_param('tagOrder');
  }

  public function getId()
  {
    return $this->id;
  }

  public function getTagName(){
    return $this->tagName;
  }

  public function getTagOrder(){
    return $this->tagOrder;
  }
}

?>