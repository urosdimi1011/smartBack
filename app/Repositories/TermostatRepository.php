<?php

namespace App\Repositories;

use App\Models\Termostat;
// use Illuminate\Database\Eloquent\Model;
class TermostatRepository implements RepositoryInterface
{
    protected $model;

    public function __construct(Termostat $model)
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
        // if(count($filter)){
        //     $this->model->filterByColumns($filter,"=");
        // }
        // return $this->model;
    }

    public function sort($column, $direction)
    {
        $this->model = $this->model->orderBy($column, $direction);
        return $this;
    }

    public function createWithRealtion($metod, $data)
    {
        return $this->model->$metod()->attach($data);
    }
    public function filterByColumns($filters, $operator)
    {
        return $this->model->where(function ($query) use ($filters, $operator) {
            foreach ($filters as $column => $value) {
                if ($column && $value) {
                    if ($operator == "like") {
                        $value = "%" . $value . "%";
                    }
                    $query->orWhere($column, $operator, $value);
                }
            }
        })->get();
    }
    public function filterByColumnsAndRelation($filters, $operator, $relation,$operatorOfFilter="OR")
    {
        $data = $this->model->with($relation)->where(function ($query) use ($filters, $operator, $operatorOfFilter) {
            foreach ($filters as $column => $value) {
                if ($column && $value) {
                    if (str_contains($column, '.')) {
                        [$relationName, $relationColumn] = explode('.', $column, 2);

                        // Detekcija za pivot
                        if (str_starts_with($relationColumn, 'pivot.')) {
                            $pivotColumn = explode('.', $relationColumn)[1];
                            $pivotTable = $this->model->{$relationName}()->getTable();
                            $query->whereHas($relationName, function ($q) use ($pivotTable,$pivotColumn, $value, $operator) {
                                $q->where($pivotTable . '.' . $pivotColumn, $operator, $value);
                            });
                        } else {
                            // Klasična relacija
                            $query->whereHas($relationName, function ($q) use ($relationColumn, $value, $operator) {
                                if ($operator === "like") {
                                    $q->where($relationColumn, 'like', "%{$value}%");
                                } elseif ($operator === 'in') {
                                    $q->whereIn($relationColumn, [$value]);
                                } elseif ($operator === 'not in') {
                                    $q->whereNotIn($relationColumn, [$value]);
                                } else {
                                    $q->where($relationColumn, $operator, $value);
                                }
                            });
                        }
                    } else {
                        // Filtriranje po glavnoj tabeli
                        if ($operator == "like") {
                            $query->orWhere($column, 'like', "%{$value}%");
                        } elseif ($operator == 'in') {
                            $query->orWhereIn($column, $value);
                        } elseif ($operator == 'not in') {
                            $query->whereNotIn($column, $value);
                        } elseif ($operatorOfFilter == 'OR') {
                            $query->orWhere($column, $operator, $value);
                        } else {
                            $query->where($column, $operator, $value);
                        }
                    }
                }
            }
        });

        return $data;
    }
}
