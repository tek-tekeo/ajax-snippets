<?php 
namespace AjaxSnippets\Api\Application\Tag;

interface ITagGetService
{
  public function handle(TagGetCommand $cmd);
}

class TagGetCommand
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

class TagSearchCommand
{
  private string $name;

  public function __construct(\WP_REST_Request $req)
  {
    $this->name = $req->get_param('name');
  }

  public function getSearchStr(){
    return $this->name;
  }
}

?>