<?php
namespace AjaxSnippets\Api\Controllers;

use \WP_REST_Request;
use \WP_REST_Response;
use AjaxSnippets\Api\Application\Logs\LogAppService;
use AjaxSnippets\Api\Application\Logs\LogRecordCommand;
use AjaxSnippets\Api\Application\Logs\LogDeleteCommand;
use AjaxSnippets\Api\Application\Logs\LogUpdateCommand;
use AjaxSnippets\Api\Application\Logs\LogGetCommand;

class LogController
{
  private $logAppService;

  public function __construct(LogAppService $logAppService)
  {
    $this->logAppService = $logAppService;
  }

  //コントローラーは入力をモデルが要求する入力に変換すること
  public function index(WP_REST_Request $req):WP_REST_Response
  {
    $cmd = new LogGetCommand(
      $req->get_param('dates'),
      $req->get_param('limit')
    );
    $res = $this->logAppService->getAllLogs($cmd);
    return new WP_REST_Response($res, 200);
  }

  public function anken(WP_REST_Request $req):WP_REST_Response
  {
    $cmd = new LogGetCommand(
      $req->get_param('dates'),
      $req->get_param('limit')
    );
    $res = $this->logAppService->getAnkenLogs($cmd);
    return new WP_REST_Response($res, 200);
  }

  public function article(WP_REST_Request $req):WP_REST_Response
  {
    $cmd = new LogGetCommand(
      $req->get_param('dates'),
      $req->get_param('limit')
    );
    $res = $this->logAppService->getArticleLogs($cmd);
    return new WP_REST_Response($res, 200);
  }

  public function click(WP_REST_Request $req):WP_REST_Response
  {
    $cmd = new LogGetCommand(
      $req->get_param('dates'),
      $req->get_param('limit')
    );
    $res = $this->logAppService->getDayPerClick($cmd);
    return new WP_REST_Response($res, 200);
  }

  public function create(WP_REST_Request $req) : WP_REST_Response
  {
    $cmd = new LogRecordCommand(
        $req->get_param('itemId'),
        $req->get_param('place')
    );

    $res = $this->logAppService->record($cmd);
    return new WP_REST_Response($res, 200);
  }
  
  // public function update(WP_REST_Request $req) : WP_REST_Response
  // {
  //   $cmd = new LogUpdateCommand(
  //     (int)$req->get_param('id'),
  //     (string)$req->get_param('logName'),
  //     (int)$req->get_param('logOrder')
  //   );
  //   $res = $this->logAppService->update($cmd);
  //   return new WP_REST_Response($res, 200);
  // }

  // public function get(WP_REST_Request $req) : WP_REST_Response
  // {
  //   $cmd = new LogGetCommand((int)$req->get_param('id'));
  //   $res = $this->logAppService->get($cmd);
  //   return new WP_REST_Response($res, 200);
  // }

  // public function delete(WP_REST_Request $req) : WP_REST_Response
  // {  
  //   $cmd = new LogDeleteCommand((int)$req->get_param('id'));
  //   $res = $this->logAppService->delete($cmd);
  //   return new WP_REST_Response( $res, 200 );
  // }
}