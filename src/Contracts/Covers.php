<?php

namespace App\Contracts;

use App\Entities\Book;

interface Covers
{
    /**
     * Returns url for cover image of given book
     * I fucking don't care how and if its byproduct is fucking world war three and contract with 
     * north korea, just fucking give me the cover ok?
     */
    public function getCover(Book $book): string;
}
