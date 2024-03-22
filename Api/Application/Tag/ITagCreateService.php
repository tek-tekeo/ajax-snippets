<?php 
namespace AjaxSnippets\Api\Application\Tag;

interface ITagCreateService
{
  public function handle(TagCreateCommand $cmd);
}

class TagCreateCommand
{
  private string $tagName;
  private int $tagOrder;

  public function __construct(\WP_REST_Request $req)
  {
    $this->tagName = $req->get_param('tagName');
    $this->tagOrder = $req->get_param('tagOrder');
  }

  public function getTagName(){
    return $this->tagName;
  }

  public function getTagOrder(){
    return $this->tagOrder;
  }
}

?>