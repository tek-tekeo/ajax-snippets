<?php
namespace AjaxSnippets\Api\Controllers;

use \WP_REST_Request;
use \WP_REST_Response;

use AjaxSnippets\Api\Application\App\IAppCreateService;
use AjaxSnippets\Api\Application\App\IAppGetService;

use AjaxSnippets\Api\Application\Ad\IAdCreateService;
use AjaxSnippets\Api\Application\Ad\IAdUpdateService;
use AjaxSnippets\Api\Application\Ad\IAdGetService;
use AjaxSnippets\Api\Application\Ad\IAdDeleteService;
use AjaxSnippets\Api\Application\Ad\AdCreateCommand;
use AjaxSnippets\Api\Application\Ad\AdUpdateCommand;
use AjaxSnippets\Api\Application\Ad\AdGetCommand;
use AjaxSnippets\Api\Application\Ad\AdDeleteCommand;

use AjaxSnippets\Api\Application\Ad\AdSearchCommand;

use AjaxSnippets\Api\Application\AdDetail\IAdDetailGetService;
use AjaxSnippets\Api\Application\App\AppCreateCommand;
use AjaxSnippets\Api\Application\App\AppGetCommand;
use AjaxSnippets\Api\Domain\Models\App\App;
use AjaxSnippets\Api\Domain\Models\App\AppId;


class AdController
{
  private $adGetService;
  private $appCreateService;
  private $appGetService;
  private $adUpdateService;
  private $adCreateService;
  private $adDeleteService;

  public function __construct(
    IAppCreateService $appCreateService,
    IAppGetService $appGetService,
    IAdCreateService $adCreateService,
    IAdUpdateService $adUpdateService,
    IAdGetService $adGetService,
    IAdDeleteService $adDeleteService
  )
  {
    $this->appCreateService = $appCreateService;
    $this->appGetService = $appGetService;
    $this->adCreateService = $adCreateService;
    $this->adUpdateService = $adUpdateService;
    $this->adGetService = $adGetService;
    $this->adDeleteService = $adDeleteService;
  }

  //全件取得
  public function index(){
    $res = $this->adGetService->getAll();
    return new WP_REST_Response($res, 200);
  }

  public function search(WP_REST_Request $req): WP_REST_Response
  {
    $cmd = new AdSearchCommand($req);
    $res = $this->adGetService->getAdsByName($cmd->getName());
    return new WP_REST_Response($res, 200);
  }

  // 新規登録
  public function create(WP_REST_Request $req): WP_REST_Response
  {
    $cmd = new AdCreateCommand($req);
    $res = $this->adCreateService->handle($cmd);
    return new WP_REST_Response($res, 200);
  }
  
  public function update(WP_REST_Request $req): WP_REST_Response
  {
    $cmd = new AdUpdateCommand($req);
    $res = $this->adUpdateService->handle($cmd);
    return new WP_REST_Response($res, 200);
  }

  public function get(WP_REST_Request $req) : WP_REST_Response
  {
    $cmd = new AdGetCommand($req);
    $res = $this->adGetService->handle($cmd);
    return new WP_REST_Response($res, 200);
  }

  public function delete(WP_REST_Request $req) : WP_REST_Response
  {  
    $cmd = new AdDeleteCommand($req);
    $res = $this->adDeleteService->handle($cmd);
    return new WP_REST_Response( $res, 200 );
  }

  public function getApp(WP_REST_Request $req): WP_REST_Response
  {
    return new WP_REST_Response([], 200);

    // $cmd = new DetailGetCommand((int)$req->get_param('detailId'));
    // $res = $this->detailGetService->handle($cmd);
    // $cmd = new AdGetCommand($res->parent->id);
    // $res = $this->adGetService->handle($cmd);
    // $res = new AppLink($res, (int)$req->get_param('noaf'));
    // return new WP_REST_Response($res, 200 );
  }

}

// class AppLink
// {
//   public function __construct($base, $noaf)
//   {
//     //名前、アプリアイコン、開発元、リンク、App Storeの画像
//     $this->id = $base->appId;
//     $this->name = $base->name;
//     $this->img = $base->img;
//     $this->dev = $base->dev;

//     //UAによる判断、アフィリエイト化するかの判断
//     $ua = $_SERVER['HTTP_USER_AGENT'];
//     if ( preg_match('/Android/ui', $ua) ) {
//       //Android系の端末
//       ($noaf == 0) ? $this->link = $base->androidAffiLink : $this->link = $base->androidLink;
//       $this->linkImg = 'https://nabettu.github.io/appreach/img/gplay_ja.png';

//     }else if ( preg_match('/iPhone|iPod|iPad/ui', $ua) ) {
//       //iOS系の端末
//       ($noaf == 0) ? $this->link = $base->iosAffiLink : $this->link = $base->iosLink;
//       $this->linkImg = 'https://nabettu.github.io/appreach/img/itune_ja.svg';
//     }else{
//       //Webブラウザからのアクセス
//       ($noaf == 0) ? $this->link = $base->affiLink : $this->link = $base->webLink;
//       $this->linkImg = '';
//     }
//   }
// }