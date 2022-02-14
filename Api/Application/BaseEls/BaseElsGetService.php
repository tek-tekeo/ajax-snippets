<?php
namespace AjaxSnippets\Api\Application\BaseEls;

use AjaxSnippets\Api\Domain\Models\BaseEls\App;
use AjaxSnippets\Api\Domain\Models\BaseEls\ParentNode;
use AjaxSnippets\Api\Domain\Models\BaseEls\BaseEls;
use AjaxSnippets\Api\Domain\Models\BaseEls\IParentNodeRepository;
use AjaxSnippets\Api\Domain\Models\BaseEls\IAppRepository;

class BaseElsGetService implements IBaseElsGetService
{
  private $parentNodeRepository;
  private $appRepository;

  public function __construct(
    IParentNodeRepository $parentNodeRepository,
    IAppRepository $appRepository
  )
  {
    $this->parentNodeRepository = $parentNodeRepository;
    $this->appRepository = $appRepository;
  }

  public function handle(BaseElsGetCommand $cmd)
  {
    $parent = $this->parentNodeRepository->ParentFindById($cmd->parentId());
    $app = $this->appRepository->AppFindById($cmd->parentId());
    if($parent == null){
      return null;
    }

    return new BaseData($parent, $app); //クライアントが直接ドメインオブジェクト　Asp()を操作できないように、DTOで対応する
  }

  public function getBaseFindByName(string $name)
  {
    $parents = $this->parentNodeRepository->ParentFindByName($name);
    $parentsData = array();
    foreach($parents as $p){
      array_push($parentsData, new ParentListData($p));
    }
    return $parentsData;
  }

  public function getAll()
  {
    $parents = $this->parentNodeRepository->getAllParent();
    $parentsData = array();
    foreach($parents as $p){
      array_push($parentsData, new ParentListData($p));
    }
    return $parentsData;
  }

}

class ParentListData
{
  public function __construct(ParentNode $parent)
  {
    $this->id = $parent->id();
    $this->name = $parent->name();
    // $this->anken = $parent->anken();
    // $this->affiLink = $parent->affiLink();
    // $this->sLink = $parent->sLink();
    // $this->aspName = $parent->aspName();
    // $this->affiImg = $parent->affiImg();
    // $this->imgTag = $parent->imgTag();
    // $this->sImgTag = $parent->sImgTag();
  }
}
class BaseData
{
  public function __construct(ParentNode $parent, App $app)
  {
    $this->id = $parent->id();
    $this->name = $parent->name();
    $this->anken = $parent->anken();
    $this->affiLink = $parent->affiLink();
    $this->sLink = $parent->sLink();
    $this->aspName = $parent->aspName();
    $this->affiImg = $parent->affiImg();
    $this->imgTag = $parent->imgTag();
    $this->sImgTag = $parent->sImgTag();
    $this->appId = $app->appId();
    $this->img = $app->img();
    $this->dev = $app->dev();
    $this->iosLink = $app->iosLink();
    $this->androidLink = $app->androidLink();
    $this->webLink = $app->webLink();
    $this->iosAffiLink = $app->iosAffiLink();
    $this->androidAffiLink = $app->androidAffiLink();
    $this->webAffiLink = $app->webAffiLink();
    $this->article = $app->article();
    $this->appOrder = $app->appOrder();
    $this->appPrice = $app->appPrice();
  }

  public function appId(){
    return $this->app->appId;
  }
  public function img(){
    return $this->app->img();
  }
  public function dev(){
    return $this->app->dev();
  }
  public function ios_link(){
    return $this->app->ios_link();
  }
  public function android_link(){
    return $this->app->android_link();
  }
  public function web_link(){
    return $this->app->web_link();
  }
  public function ios_affi_link(){
    return $this->app->ios_affi_link();
  }
  public function android_affi_link(){
    return $this->app->android_affi_link();
  }
  public function web_affi_link(){
    return $this->app->web_affi_link();
  }
  public function article(){
    return $this->app->article();
  }
  public function app_order(){
    return $this->app->app_order();
  }
  public function app_price(){
    return $this->app->app_price();
  }

  public function id(){
    return $this->parent->id();
  }

  public function name(){
    return $this->name;
  }
}
