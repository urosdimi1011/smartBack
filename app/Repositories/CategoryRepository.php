<?php

namespace App\Repositories;

use App\Models\Category;
class CategoryRepository implements RepositoryInterface
{
    protected $model;

    public function __construct(Category $model)
    {
        $this->model = $model;
    }
    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->find($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $model = $this->model->find($id);
        $model->update($data);
        return $model;
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }

    public function createMoreData(array $data)
    {
        return $this->model->insert($data);

    }

    public function createOrUpdate(array ...$data)
    {
        return $this->model->updateOrCreate(...$data);
    }

    public function getAllWithRelation(array | string $relation)
    {
        return $this->model->with($relation)->get();
    }

    public function sort($column, $direction)
    {
        $this->model= $this->model->orderBy($column, $direction);
        return $this;
    }
    public function createWithRealtion($metod,$data){
        return $this->model->$metod()->attach($data);
    }
    public function filterByColumns($filters, $operator)
    {
        return $this->model->where(function ($query) use ($filters,$operator) {
            foreach ($filters as $column => $value) {
                if ($column && $value) {
                    if($operator == "like"){
                        $query->orWhere($column, 'like', "%{$value}%");
                    }
                    elseif ($operator == 'in') {
                        $query->orWhereIn($column, $value);
                    } elseif ($operator == 'not in') {
                        $query->whereNotIn($column, $value);
                    } else {
                        $query->orWhere($column, $operator, $value);
                    }
                }
            }
        });
    }
}
