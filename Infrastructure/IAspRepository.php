<?php
namespace AjaxSnippets\Infrastructure\Repository;

use AjaxSneppets\Domain\Models\Asp;
use AjaxSneppets\Domain\Models\AspId;

interface IAspRepository
{
    public function save(Asp $asp) : bool;
    public function delete(Asp $asp) : bool;
    public function AspFindById(AspId $aspId);
    public function AspFindByName(Asp $asp);
}