<?php
namespace App\Services;

use App\Repositories\CategoryRepository;
use App\Services\OwnService;

class CategoryService extends OwnService
{
    public function __construct(CategoryRepository $atributi)
    {
        parent::__construct($atributi);
    }   
}
