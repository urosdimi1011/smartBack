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
    public function filterByColumnsAndRelation($filters, $operator, $relation, $relationFilters = [])
    {
        //Ovo ovde cu sad da napravim samo za jednu relaciju i filtriranje u njoj, 
        //sto znaci da ovo mora da se resi kad bude bilo vise relacija i izmedju 
        //razlicitih tabela neka filtriranja
        // dd($relation);
        // $data = $this->model->where(function ($query) use ($filters, $operator) {
        //     foreach ($filters as $column => $value) {
        //         if ($column && $value) {
        //             if ($operator == "like") {
        //                 $value = "%" . $value . "%";
        //             }

        //             if (str_contains($column, '.')) {
        //                 [$rel, $col] = explode('.', $column);
        //                 $query->whereHas($rel, function ($q) use ($col, $operator, $value) {
        //                     $q->where($col, $operator, (int)$value);
        //                 });
        //             } else {
        //                 $query->orWhere($column, $operator, $value);
        //             }
        //         }
        //     }
        // })->with([
        //     $relation => function ($query) use ($relationFilters) {
        //         // dd($relationFilters);
        //         foreach ($relationFilters as $column => $value) {
        //             // dd($column);
        //             if ($column && $value) {
        //                 $query->where($column, $value);
        //             }
        //         }
        //         // $query->with('category'); 
        //     },
        //     'devices.category'
        // ])->get();
        // return $data;
    }
}
