<?php
namespace AjaxSnippets\Api\Controllers;

use \WP_REST_Request;
use \WP_REST_Response;
use AjaxSnippets\Api\Application\TagLinks\TagLinkAppService;
use AjaxSnippets\Api\Application\TagLinks\TagLinkDeleteCommand;
use AjaxSnippets\Api\Application\TagLinks\TagLinkUpdateCommand;
use AjaxSnippets\Api\Application\TagLinks\TagLinkCreateCommand;
use AjaxSnippets\Api\Application\TagLinks\TagLinkGetCommand;

class TagLinkController
{
  private $tagAppService;

  public function __construct(TagLinkAppService $tagLinkAppService)
  {
    $this->tagLinkAppService = $tagLinkAppService;
  }

  public function create(WP_REST_Request $req) : WP_REST_Response
  {
    $cmd = new TagLinkCreateCommand(
      $req->get_param('itemId'),
      $req->get_param('tagId')
    );
    $res = $this->tagLinkAppService->create($cmd);
    return new WP_REST_Response($res, 200);
  }
  
  public function update(WP_REST_Request $req) : WP_REST_Response
  {
    $cmd = new TagLinkUpdateCommand(
      (int)$req->get_param('id'),
      (string)$req->get_param('tagName'),
      (int)$req->get_param('tagOrder')
    );
    $res = $this->tagLinkAppService->update($cmd);
    return new WP_REST_Response($res, 200);
  }

  public function get(WP_REST_Request $req) : WP_REST_Response
  {
    $cmd = new TagLinkGetCommand((int)$req->get_param('itemId'));
    $res = $this->tagLinkAppService->get($cmd);
    return new WP_REST_Response((array)$res, 200);
  }

  public function delete(WP_REST_Request $req) : WP_REST_Response
  {  
    $cmd = new TagLinkDeleteCommand((int)$req->get_param('itemId'));
    $res = $this->tagLinkAppService->delete($cmd);
    return new WP_REST_Response( $res, 200 );
  }

  //ショートコードのタグランキングを作る
  public function getTagRanking(WP_REST_Request $req) : WP_REST_Response
  {
    $cmd = new TagRankingCommand((int)$req->get_param('tagId'));
    $res = $this->tagLinkAppService->createTagRanking($cmd);
    return new WP_REST_Response( $res, 200 );
  }
}

class TagRankingCommand
{
  private $tagId;

  public function __construct(int $tagId)
  {
    $this->tagId = $tagId;
  }

  public function getTagId()
  {
    return $this->tagId;
  }
}