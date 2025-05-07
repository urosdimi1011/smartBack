<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService,
    ) {}

    public function getAll(){
//        if($request->has('all')){
//            $categories = $this->categoryService->getAll();
//        }
//        else{
//            $categories = $this->categoryService->filterByColumns(['name'=>['Heating','Cooling']],'not in')->get();
//        }
        $categories = $this->categoryService->getAll();
        return response()->json(["categories"=>$categories],200);
    }
}
