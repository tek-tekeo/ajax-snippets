<?php
namespace AjaxSnippets\Api\Application\App;

interface IAppCreateService
{
  public function handle(AppCreateCommand $cmd);
}

class AppCreateCommand
{
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
    $this->name = (string)$req->get_param('name');
    $this->img = (string)$req->get_param('img');
    $this->dev = (string)$req->get_param('dev');
    $this->iosLink = (string)$req->get_param('iosLink');
    $this->androidLink = (string)$req->get_param('androidLink');
    $this->webLink = (string)$req->get_param('webLink');
    $this->iosAffiLink = (string)$req->get_param('iosAffiLink');
    $this->androidAffiLink = (string)$req->get_param('androidAffiLink');
    $this->webAffiLink = (string)$req->get_param('webAffiLink');
    $this->article = (string)$req->get_param('article');
    $this->appOrder = (int)$req->get_param('appOrder') ?? 0;
    $this->appPrice = (int)$req->get_param('appPrice') ?? 0;
  }

  public function getName(): string
  {
    return $this->name;
  }
  
  public function getImg(): string
  {
    return $this->img;
  }

  public function getDev(): string
  {
    return $this->dev;
  }

  public function getIosLink(): string
  {
    return $this->iosLink;
  }

  public function getAndroidLink(): string
  {
    return $this->androidLink;
  }

  public function getWebLink(): string
  {
    return $this->webLink;
  }

  public function getIosAffiLink(): string
  {
    return $this->iosAffiLink;
  }

  public function getAndroidAffiLink(): string
  {
    return $this->androidAffiLink;
  }

  public function getWebAffiLink(): string
  {
    return $this->webAffiLink;
  }

  public function getArticle(): string
  {
    return $this->article;
  }

  public function getAppOrder(): int
  {
    return $this->appOrder;
  }

  public function getAppPrice(): int
  {
    return $this->appPrice;
  }

}