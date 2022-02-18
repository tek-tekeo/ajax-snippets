<?php
namespace AjaxSnippets\Api\Controllers;

use \WP_REST_Request;
use \WP_REST_Response;

use AjaxSnippets\Api\Application\BaseEls\IBaseElsUpdateService;
use AjaxSnippets\Api\Application\BaseEls\BaseElsUpdateCommand;
use AjaxSnippets\Api\Application\BaseEls\IBaseElsGetService;
use AjaxSnippets\Api\Application\BaseEls\BaseElsGetCommand;
use AjaxSnippets\Api\Application\BaseEls\IBaseElsCreateService;
use AjaxSnippets\Api\Application\BaseEls\BaseElsCreateCommand;

use AjaxSnippets\Api\Domain\Models\BaseEls\ParentNode;
use AjaxSnippets\Api\Domain\Models\BaseEls\App;


class BaseController
{
  private $baseElsGetService;
  private $baseElsUpdateService;
  private $baseElsCreateService;

  public function __construct(
    IBaseElsCreateService $baseElsCreateService,
    IBaseElsUpdateService $baseElsUpdateService,
    IBaseElsGetService $baseElsGetService
  )
  {
    $this->baseElsCreateService = $baseElsCreateService;
    $this->baseElsGetService = $baseElsGetService;
    $this->baseElsUpdateService = $baseElsUpdateService;
  }

  //コントローラーは入力をモデルが要求する入力に変換すること
  public function index(){
    //全件取得
    $res = $this->baseElsGetService->getAll();
    return new WP_REST_Response($res, 200);
  }

  public function search(WP_REST_Request $req): WP_REST_Response
  {
    $res = $this->baseElsGetService->getBaseFindByName((string)$req->get_param('name'));
    return new WP_REST_Response($res, 200);
  }

  public function create(WP_REST_Request $req) : WP_REST_Response
  {
    $cmd = new BaseElsCreateCommand(
      new ParentNode(
        (int)$req->get_param('id'),
        (string)$req->get_param('name'),
        (string)$req->get_param('anken'),
        (string)$req->get_param('affiLink'),
        (string)$req->get_param('sLink'),
        (string)$req->get_param('aspName'),
        (string)$req->get_param('affiImg'),
        (string)$req->get_param('imgTag'),
        (string)$req->get_param('sImgTag'),
        (int)$req->get_param('affiImgWidth'),
        (int)$req->get_param('affiImgHeight')
      ),
      new App(
        (int)$req->get_param('appId'),
        (string)$req->get_param('img'),
        (string)$req->get_param('dev'),
        (string)$req->get_param('iosLink'),
        (string)$req->get_param('androidLink'),
        (string)$req->get_param('webLink'),
        (string)$req->get_param('iosAffiLink'),
        (string)$req->get_param('androidAffiLink'),
        (string)$req->get_param('webAffiLink'),
        (string)$req->get_param('article'),
        (int)$req->get_param('appOrder'),
        (int)$req->get_param('appPrice')
      )
    );

    $res = $this->baseElsCreateService->handle($cmd);
    return new WP_REST_Response($res, 200);
  }
  
  public function update(WP_REST_Request $req) : WP_REST_Response
  {
    $cmd = new BaseElsUpdateCommand(
      new ParentNode(
        (int)$req->get_param('id'),
        (string)$req->get_param('name'),
        (string)$req->get_param('anken'),
        (string)$req->get_param('affiLink'),
        (string)$req->get_param('sLink'),
        (string)$req->get_param('aspName'),
        (string)$req->get_param('affiImg'),
        (string)$req->get_param('imgTag'),
        (string)$req->get_param('sImgTag'),
        (int)$req->get_param('affiImgWidth'),
        (int)$req->get_param('affiImgHeight')
      ),
      new App(
        (int)$req->get_param('appId'),
        (string)$req->get_param('img'),
        (string)$req->get_param('dev'),
        (string)$req->get_param('iosLink'),
        (string)$req->get_param('androidLink'),
        (string)$req->get_param('webLink'),
        (string)$req->get_param('iosAffiLink'),
        (string)$req->get_param('androidAffiLink'),
        (string)$req->get_param('webAffiLink'),
        (string)$req->get_param('article'),
        (int)$req->get_param('appOrder'),
        (int)$req->get_param('appPrice')
      )
    );
    $res = $this->baseElsUpdateService->handle($cmd);
    return new WP_REST_Response($res, 200);
  }

  public function get(WP_REST_Request $req) : WP_REST_Response
  {
    $cmd = new BaseElsGetCommand((int)$req->get_param('id'));
    $res = $this->baseElsGetService->handle($cmd);
    return new WP_REST_Response($res, 200);
  }

  // public function delete(WP_REST_Request $req) : WP_REST_Response
  // {  
  //   $cmd = new AspDeleteCommand($req->get_param('id'));
  //   $res = $this->aspDeleteService->handle($cmd);
  //   return new WP_REST_Response( $res, 200 );
  // }
}