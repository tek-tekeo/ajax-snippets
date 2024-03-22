<?php
namespace AjaxSnippets\Api\Controllers;

use \WP_REST_Request;
use \WP_REST_Response;
use AjaxSnippets\Api\Application\App\IAppCreateService;
use AjaxSnippets\Api\Application\App\IAppUpdateService;
use AjaxSnippets\Api\Application\App\IAppGetService;
use AjaxSnippets\Api\Application\App\IAppDeleteService;
use AjaxSnippets\Api\Application\App\AppDeleteCommand;
use AjaxSnippets\Api\Application\App\AppUpdateCommand;
use AjaxSnippets\Api\Application\App\AppCreateCommand;
use AjaxSnippets\Api\Application\App\AppGetCommand;

class AppController
{
  private $appCreateService;
  private $appUpdateService;
  private $appGetService;
  private $appDeleteService;

  public function __construct(
    IAppCreateService $appCreateService,
    IAppUpdateService $appUpdateService,
    IAppGetService $appGetService,
    IAppDeleteService $appDeleteService
    )
  {
    $this->appCreateService = $appCreateService;
    $this->appUpdateService = $appUpdateService;
    $this->appGetService = $appGetService;
    $this->appDeleteService = $appDeleteService;
  }

  //コントローラーは入力をモデルが要求する入力に変換すること
  public function index(){
    $res = $this->appGetService->getAll();
    return new WP_REST_Response($res, 200);
  }

  public function create(WP_REST_Request $req) : WP_REST_Response
  {
    $cmd = new AppCreateCommand($req);
    $res = $this->appCreateService->handle($cmd);
    return new WP_REST_Response($res, 200);
  }
  
  public function update(WP_REST_Request $req) : WP_REST_Response
  {
    $cmd = new AppUpdateCommand($req);
    $res = $this->appUpdateService->handle($cmd);
    return new WP_REST_Response($res, 200);
  }

  public function get(WP_REST_Request $req) : WP_REST_Response
  {
    $cmd = new AppGetCommand($req);
    $res = $this->appGetService->handle($cmd);
    return new WP_REST_Response($res, 200);
  }

  public function delete(WP_REST_Request $req) : WP_REST_Response
  {  
    $cmd = new AppDeleteCommand($req);
    $res = $this->appDeleteService->handle($cmd);
    return new WP_REST_Response( $res, 200 );
  }
}