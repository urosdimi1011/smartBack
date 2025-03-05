<?php 
namespace App\Repositories;


interface RepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function createMoreData(array $data);
    public function createOrUpdate(array $data);
    public function createWithRealtion($method,array $data);
    public function getAllWithRelation(string $relation);
    public function filterByColumns($filters,$operator);
    public function update($id, array $data);
    public function delete($id);
}
