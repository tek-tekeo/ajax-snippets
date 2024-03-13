<?php
namespace AjaxSnippets\Api\Controllers;

use \WP_REST_Request;
use \WP_REST_Response;
use AjaxSnippets\Api\Application\Log\ILogCreateService;
use AjaxSnippets\Api\Application\Log\ILogDeleteService;
use AjaxSnippets\Api\Application\Log\ILogGetService;
use AjaxSnippets\Api\Application\Log\LogDeleteCommand;
use AjaxSnippets\Api\Application\Log\LogGetCommand;

class LogController
{
  public function __construct(
    private ILogCreateService $logCreateService,
    private ILogGetService $logGetService,
    private ILogDeleteService $logDeleteService
    )
  {}

  public function index(WP_REST_Request $req): WP_REST_Response
  {
    $cmd = new LogGetCommand($req);
    $res = $this->logGetService->handle($cmd);
    return new WP_REST_Response($res, 200);
  }

  public function anken(WP_REST_Request $req):WP_REST_Response
  {
    $cmd = new LogGetCommand($req);
    $res = $this->logGetService->getAnkenLogs($cmd);
    return new WP_REST_Response($res, 200);
  }

  public function article(WP_REST_Request $req):WP_REST_Response
  {
    $cmd = new LogGetCommand($req);
    $res = $this->logGetService->getArticleLogs($cmd);
    return new WP_REST_Response($res, 200);
  }

  public function click(WP_REST_Request $req):WP_REST_Response
  {
    $cmd = new LogGetCommand($req);
    $res = $this->logGetService->getDayPerClick($cmd);
    return new WP_REST_Response($res, 200);
  }

  public function create(WP_REST_Request $req) : WP_REST_Response
  {
    $cmd = new LogCreateCommand($req);
    $res = $this->logCreateService->record($cmd);
    return new WP_REST_Response($res, 200);
  }

  public function delete(WP_REST_Request $req) : WP_REST_Response
  {  
    $cmd = new LogDeleteCommand($req);
    $res = $this->logDeleteService->delete($cmd);
    return new WP_REST_Response( $res, 200 );
  }
}