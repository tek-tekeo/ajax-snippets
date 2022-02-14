<?php
namespace AjaxSnippets\Api\Controllers;

use \WP_REST_Request;
use \WP_REST_Response;
use AjaxSnippets\Api\Application\Details\IDetailCreateService;
use AjaxSnippets\Api\Application\Details\DetailCreateCommand;
use AjaxSnippets\Api\Application\Details\IDetailUpdateService;
use AjaxSnippets\Api\Application\Details\DetailUpdateCommand;
use AjaxSnippets\Api\Application\Details\IDetailGetService;
use AjaxSnippets\Api\Application\Details\DetailGetCommand;
use AjaxSnippets\Api\Domain\Models\BaseEls\ParentNode;
use AjaxSnippets\Api\Domain\Models\Details\Detail;


class DetailController
{
  private $detailGetService;
  private $detailUpdateService;
  private $detailCreateService;

  public function __construct(
    IDetailUpdateService $detailUpdateService,
    IDetailGetService $detailGetService,
    IDetailCreateService $detailCreateService
  )
  {
    $this->detailGetService = $detailGetService;
    $this->detailUpdateService = $detailUpdateService;
    $this->detailCreateService = $detailCreateService;
  }

  //コントローラーは入力をモデルが要求する入力に変換すること
  public function index(){
    //全件取得
    $res = $this->detailGetService->getDetailsFindByName('');
    return new WP_REST_Response($res, 200);
  }
  public function search(WP_REST_Request $req): WP_REST_Response
  {
    $res = $this->detailGetService->getDetailsFindByName((string)$req->get_param('name'));
    return new WP_REST_Response($res, 200);
  }

  public function create(WP_REST_Request $req) : WP_REST_Response
  {
    $cmd = new DetailCreateCommand(
      new Detail(
        0,
        new ParentNode((int)$req->get_param('parent')['id']),
        (string)$req->get_param('itemName'),
        (string)$req->get_param('officialItemLink'),
        (string)$req->get_param('affiItemLink'),
        (string)$req->get_param('detailImg'),
        (string)$req->get_param('amazonAsin'),
        (string)$req->get_param('rakutenId'),
        (string)json_encode($req->get_param('rchart')),
        (string)json_encode($req->get_param('info')),
        (string)$req->get_param('review'),
        (int)$req->get_param('isShowUrl'),
        (int)$req->get_param('sameParent')
      )
    );
    $res = $this->detailCreateService->handle($cmd);
    return new WP_REST_Response($res, 200);
  }
  
  public function update(WP_REST_Request $req) : WP_REST_Response
  {
    $cmd = new DetailUpdateCommand(
      new Detail(
        (int)$req->get_param('id'),
        new ParentNode((int)$req->get_param('parent')['id']),
        (string)$req->get_param('itemName'),
        (string)$req->get_param('officialItemLink'),
        (string)$req->get_param('affiItemLink'),
        (string)$req->get_param('detailImg'),
        (string)$req->get_param('amazonAsin'),
        (string)$req->get_param('rakutenId'),
        (string)json_encode($req->get_param('rchart')),
        (string)json_encode($req->get_param('info')),
        (string)$req->get_param('review'),
        (int)$req->get_param('isShowUrl'),
        (int)$req->get_param('sameParent')
      )
    );
    $res = $this->detailUpdateService->handle($cmd);
    return new WP_REST_Response($res, 200);
  }

  public function get(WP_REST_Request $req) : WP_REST_Response
  {
    $cmd = new DetailGetCommand((int)$req->get_param('id'));
    $res = $this->detailGetService->handle($cmd);
    return new WP_REST_Response($res, 200);
  }

  // public function delete(WP_REST_Request $req) : WP_REST_Response
  // {  
  //   $cmd = new AspDeleteCommand($req->get_param('id'));
  //   $res = $this->aspDeleteService->handle($cmd);
  //   return new WP_REST_Response( $res, 200 );
  // }
}