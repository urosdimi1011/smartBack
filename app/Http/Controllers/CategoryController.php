<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
// use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryService $categoryService,
    ) {}

    public function getAll(){
        $categories = $this->categoryService->getAll();
        return response()->json(["categories"=>$categories],200);
    }
}
