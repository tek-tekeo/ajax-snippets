<?php
namespace AjaxSnippets\Api\Domain\Models\Asp;

use AjaxSnippets\Api\Domain\Models\Asp\AspId;
use AjaxSnippets\Api\Domain\Models\Asp\Asp;

interface IAspRepository
{
    public function save(Asp $asp) : AspId;
    public function delete(AspId $aspId) : bool;
    public function getAll():array;
    public function AspFindById(AspId $aspId);
    public function AspFindByName(string $aspName) : ?Asp;
}