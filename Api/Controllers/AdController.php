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

}