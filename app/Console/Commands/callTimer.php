<?php

namespace App\Console\Commands;

use App\Http\Controllers\TimerController;
use App\Services\TimerService;
use Illuminate\Console\Command;

class callTimer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timer:process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pokreće metod processTimers u TimerController-u';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $timerService = app(TimerService::class);
        $controller = new TimerController($timerService);
        $controller->processTimers();

        $this->info('Metoda processTimers je uspešno pozvana!');
    }
}
