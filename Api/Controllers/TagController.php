<?php
namespace AjaxSnippets\Api\Controllers;

use \WP_REST_Request;
use \WP_REST_Response;
use AjaxSnippets\Api\Application\Tag\ITagCreateService;
use AjaxSnippets\Api\Application\Tag\ITagUpdateService;
use AjaxSnippets\Api\Application\Tag\ITagGetService;
use AjaxSnippets\Api\Application\Tag\ITagDeleteService;
use AjaxSnippets\Api\Application\Tag\TagCreateCommand;
use AjaxSnippets\Api\Application\Tag\TagUpdateCommand;
use AjaxSnippets\Api\Application\Tag\TagGetCommand;
use AjaxSnippets\Api\Application\Tag\TagDeleteCommand;

class TagController
{

  public function __construct(
    private ITagCreateService $tagCreateService,
    private ITagUpdateService $tagUpdateService,
    private ITagGetService $tagGetService,
    private ITagDeleteService $tagDeleteService
    ){}

  public function index(){
    $res = $this->tagGetService->getAll();
    return new WP_REST_Response($res, 200);
  }

  public function create(WP_REST_Request $req) : WP_REST_Response
  {
    $cmd = new TagCreateCommand($req);
    $res = $this->tagCreateService->handle($cmd);
    return new WP_REST_Response($res, 200);
  }
  
  public function update(WP_REST_Request $req) : WP_REST_Response
  {
    $cmd = new TagUpdateCommand($req);
    $res = $this->tagUpdateService->handle($cmd);
    return new WP_REST_Response($res, 200);
  }

  public function get(WP_REST_Request $req) : WP_REST_Response
  {
    $cmd = new TagGetCommand($req);
    $res = $this->tagGetService->handle($cmd);
    return new WP_REST_Response($res, 200);
  }

  public function delete(WP_REST_Request $req) : WP_REST_Response
  {  
    $cmd = new TagDeleteCommand($req);
    $res = $this->tagDeleteService->handle($cmd);
    return new WP_REST_Response( $res, 200 );
  }

  public function getEditorList(WP_REST_Request $req): WP_REST_Response
  {
    $res = $this->tagGetService->search((string)$req->get_param('name'));
    return new WP_REST_Response($res, 200);
  }

}