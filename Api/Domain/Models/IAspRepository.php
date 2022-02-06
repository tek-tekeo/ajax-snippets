<?php
namespace AjaxSnippets\Api\Domain\Models;

use AjaxSnippets\Api\Domain\Models\AspId;
use AjaxSnippets\Api\Domain\Models\Asp;

interface IAspRepository
{
    public function save(Asp $asp) : bool;
    public function delete(Asp $asp) : bool;
    public function getAll():array;
    public function AspFindById(AspId $aspId);
    public function AspFindByName(Asp $asp);
}