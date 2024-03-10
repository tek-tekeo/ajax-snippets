<?php
namespace AjaxSnippets\Api\Application\App;

interface IAppUpdateService
{
  public function handle(AppUpdateCommand $cmd);
}

class AppUpdateCommand
{
  private int $id;
  private string $name;
  private string $img;
  private string $dev;
  private string $iosLink;
  private string $androidLink;
  private string $webLink;
  private string $iosAffiLink;
  private string $androidAffiLink;
  private string $webAffiLink;
  private string $article;
  private int $appOrder;
  private int $appPrice;

  public function __construct(\WP_REST_Request $req)
  {
    $this->id = $req->get_param('id');
    $this->name = $req->get_param('name');
    $this->img = $req->get_param('img');
    $this->dev = $req->get_param('dev');
    $this->iosLink = $req->get_param('iosLink');
    $this->androidLink = $req->get_param('androidLink');
    $this->webLink = $req->get_param('webLink');
    $this->iosAffiLink = $req->get_param('iosAffiLink');
    $this->androidAffiLink = $req->get_param('androidAffiLink');
    $this->webAffiLink = $req->get_param('webAffiLink');
    $this->article = $req->get_param('article');
    $this->appOrder = $req->get_param('appOrder');
    $this->appPrice = $req->get_param('appPrice');
  }

  public function getId(){
    return $this->id;
  }

  public function getName(){
    return $this->name;
  }

  public function getImg(){
    return $this->img;
  }

  public function getDev(){
    return $this->dev;
  }

  public function getIosLink(){
    return $this->iosLink;
  }

  public function getAndroidLink(){
    return $this->androidLink;
  }

  public function getWebLink(){
    return $this->webLink;
  }

  public function getIosAffiLink(){
    return $this->iosAffiLink;
  }

  public function getAndroidAffiLink(){
    return $this->androidAffiLink;
  }

  public function getWebAffiLink(){
    return $this->webAffiLink;
  }

  public function getArticle(){
    return $this->article;
  }

  public function getAppOrder(){
    return $this->appOrder;
  }

  public function getAppPrice(){
    return $this->appPrice;
  }
}
