<?php 
namespace AjaxSnippets\Api\Application\Tag;

interface ITagDeleteService
{
  public function handle(TagDeleteCommand $cmd);
}

class TagDeleteCommand
{
  private int $id;

  public function __construct(\WP_REST_Request $req)
  {
    $this->id = $req->get_param('id');
  }

  public function getId(){
    return $this->id;
  }
}

?>