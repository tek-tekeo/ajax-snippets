<?php
namespace AjaxSnippets\Api\Domain\Models\Asps;

use AjaxSnippets\Api\Domain\Models\Asps\AspId;
use AjaxSnippets\Api\Domain\Models\Asps\Asp;

interface IAspRepository
{
    public function save(Asp $asp) : bool;
    public function delete(Asp $asp) : bool;
    public function getAll():array;
    public function AspFindById(AspId $aspId);
    public function AspFindByName(string $aspName) : ?Asp;
}