<?php

namespace App\Repository;


interface RepositoryInterface
{
    public function save(object $entity) : void;
}