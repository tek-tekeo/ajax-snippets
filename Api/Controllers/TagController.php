<?php
namespace AjaxSnippets\Api\Controllers;

use \WP_REST_Request;
use \WP_REST_Response;
use AjaxSnippets\Api\Application\Tags\TagAppService;
use AjaxSnippets\Api\Application\Tags\TagDeleteCommand;
use AjaxSnippets\Api\Application\Tags\TagUpdateCommand;
use AjaxSnippets\Api\Application\Tags\TagCreateCommand;
use AjaxSnippets\Api\Application\Tags\TagGetCommand;

class TagController
{
  private $tagAppService;

  public function __construct(TagAppService $tagAppService)
  {
    $this->tagAppService = $tagAppService;
  }

  //コントローラーは入力をモデルが要求する入力に変換すること
  public function index(){
    //全件取得
    $res = $this->tagAppService->getAll();
    return new WP_REST_Response($res, 200);
  }

  public function create(WP_REST_Request $req) : WP_REST_Response
  {
    $cmd = new TagCreateCommand(
      $req->get_param('tagName'),
      $req->get_param('tagOrder')
    );
    $res = $this->tagAppService->create($cmd);
    return new WP_REST_Response($res, 200);
  }
  
  public function update(WP_REST_Request $req) : WP_REST_Response
  {
    $cmd = new TagUpdateCommand(
      (int)$req->get_param('id'),
      (string)$req->get_param('tagName'),
      (int)$req->get_param('tagOrder')
    );
    $res = $this->tagAppService->update($cmd);
    return new WP_REST_Response($res, 200);
  }

  public function get(WP_REST_Request $req) : WP_REST_Response
  {
    $cmd = new TagGetCommand((int)$req->get_param('id'));
    $res = $this->tagAppService->get($cmd);
    return new WP_REST_Response($res, 200);
  }

  public function delete(WP_REST_Request $req) : WP_REST_Response
  {  
    $cmd = new TagDeleteCommand((int)$req->get_param('id'));
    $res = $this->tagAppService->delete($cmd);
    return new WP_REST_Response( $res, 200 );
  }
}