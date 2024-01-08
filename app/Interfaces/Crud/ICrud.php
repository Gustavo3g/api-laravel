<?php

namespace App\Interfaces\Crud;

interface ICrud
{

    public function getAll(): object;

    public function getOne(int $id): object;

    public function create(array $attributes): object;

    public function update(int $id, array $attributes): bool;

    public function delete(int $id): bool;


}
