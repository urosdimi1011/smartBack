<?php
namespace App\Services;
use App\Repositories\UserRepository;
use App\Services\OwnService;

class UserService extends OwnService
{
    public function __construct(public UserRepository $atributi)
    {
        parent::__construct($atributi);
    }
}
