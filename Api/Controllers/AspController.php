<?php
namespace AjaxSnippets\Api\Controllers;

use \WP_REST_Request;
use \WP_REST_Response;
use AjaxSnippets\Api\Application\Asp\IAspCreateService;
use AjaxSnippets\Api\Application\Asp\IAspUpdateService;
use AjaxSnippets\Api\Application\Asp\IAspGetService;
use AjaxSnippets\Api\Application\Asp\IAspDeleteService;
use AjaxSnippets\Api\Application\Asp\AspCreateCommand;
use AjaxSnippets\Api\Application\Asp\AspUpdateCommand;
use AjaxSnippets\Api\Application\Asp\AspGetCommand;
use AjaxSnippets\Api\Application\Asp\AspDeleteCommand;

class AspController
{

  public function __construct(
    private IAspCreateService $aspCreateService,
    private IAspUpdateService $aspUpdateService,
    private IAspGetService $aspGetService,
    private IAspDeleteService $aspDeleteService
  ){}

  public function index(): WP_REST_Response
  {
    //前件取得
    $res = $this->aspGetService->getAll();
    return new WP_REST_Response($res, 200);
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