<?php
namespace AjaxSnippets\Api\Application\Ad;

interface IAdGetService
{
  public function handle(AdGetCommand $cmd);
  public function getAdsByName(string $name);
  public function getAll();
}

class AdGetCommand
{
  private int $id;

  public function __construct(\WP_REST_Request $req)
  {
    $this->id = (int)$req->get_param('id');
  }

  public function getId()
  {
    return $this->id;
  }
}

class AdSearchCommand
{
  private string $name;

  public function __construct(\WP_REST_Request $req)
  {
    $this->name = (string)$req->get_param('name');
  }

  public function getName()
  {
    return $this->name;
  }
}