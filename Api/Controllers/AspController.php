<?php
namespace AjaxSnippets\Api\Controllers;

use \WP_REST_Request;
use \WP_REST_Response;
use AjaxSnippets\Api\Application\Asp\IAspCreateService;
use AjaxSnippets\Api\Application\Asp\IAspUpdateService;
use AjaxSnippets\Api\Application\Asp\IAspGetService;
use AjaxSnippets\Api\Application\Asp\IAspDeleteService;
use AjaxSnippets\Api\Application\Asp\AspDeleteCommand;
use AjaxSnippets\Api\Application\Asp\AspUpdateCommand;
use AjaxSnippets\Api\Application\Asp\AspCreateCommand;
use AjaxSnippets\Api\Application\Asp\AspGetCommand;

class AspController
{
  private $aspCreateService;
  private $aspUpdateService;
  private $aspGetService;
  private $aspDeleteService;

  public function __construct(
    IAspCreateService $aspCreateService,
    IAspUpdateService $aspUpdateService,
    IAspGetService $aspGetService,
    IAspDeleteService $aspDeleteService
    )
  {
    $this->aspCreateService = $aspCreateService;
    $this->aspUpdateService = $aspUpdateService;
    $this->aspGetService = $aspGetService;
    $this->aspDeleteService = $aspDeleteService;
  }

  //コントローラーは入力をモデルが要求する入力に変換すること
  public function index(){
    //前件取得
    $res = $this->aspGetService->getAll();
    return $res;
  }

  public function create(WP_REST_Request $req) : WP_REST_Response
  {
    $cmd = new AspCreateCommand($req);
    $res = $this->aspCreateService->handle($cmd);
    return new WP_REST_Response($res, 200);
  }
  
  public function update(WP_REST_Request $req) : WP_REST_Response
  {
    $cmd = new AspUpdateCommand($req);
    $res = $this->aspUpdateService->handle($cmd);
    return new WP_REST_Response($res, 200);
  }

  public function get(WP_REST_Request $req) : WP_REST_Response
  {
    $cmd = new AspGetCommand($req);
    $res = $this->aspGetService->handle($cmd);
    return new WP_REST_Response($res, 200);
  }

  public function delete(WP_REST_Request $req) : WP_REST_Response
  {  
    $cmd = new AspDeleteCommand($req);
    $res = $this->aspDeleteService->handle($cmd);
    return new WP_REST_Response( $res, 200 );
  }
}