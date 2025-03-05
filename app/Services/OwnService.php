<?php
namespace App\Services;
use App\Repositories\RepositoryInterface;

abstract class OwnService
{
    protected $model;

    public function __construct(RepositoryInterface $model)
    {
        $this->model = $model;
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function getAllWithRelation(array | string $relation)
    {
        return $this->model->getAllWithRelation($relation);
    }


    public function getById($id)
    {
        return $this->model->find($id);
    }

    public function create($data)
    {
        return $this->model->create($data);
    }

    public function createMoreData(array $data)
    {
        return $this->model->createMoreData($data);
    }

    public function createWithRealtion($method,$data){
        return $this->model->createWithRealtion($method,$data);
    }


    public function createOrUpdate(array ...$data){
        return $this->model->createOrUpdate(...$data);
    }

    public function update($id, $data)
    {
        $model = $this->model->find($id);

        if (!$model) {
            return null;
        }
        $model->fill($data);
        $model->save();

        return $model;
    }

    public function delete($id)
    {
        $model = $this->model->find($id);

        if (!$model) {
            return null;
        }

        $model->delete();

        return $model;
    }

    public function filterByColumns($filters, $operator)
    {
        return $this->model->filterByColumns($filters,$operator);
    }
}
