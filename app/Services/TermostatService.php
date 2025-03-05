<?php

namespace App\Services;

// use App\Models\Timer;
// use Illuminate\Support\Facades\DB;
use App\Repositories\TermostatRepository;
use App\Services\OwnService;
// use Exception;
// use Carbon\Carbon;

class TermostatService extends OwnService
{
    public function __construct(protected TermostatRepository $atributi)
    {
        parent::__construct($atributi);
    }
}
