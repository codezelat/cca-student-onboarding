<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateRegisterIds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'register:update-ids';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update register IDs for existing CCA registrations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $registrations = \App\Models\CCARegistration::whereNull('register_id')->get();
        
        $this->info("Found {$registrations->count()} registrations without register IDs");
        
        $bar = $this->output->createProgressBar($registrations->count());
        $bar->start();
        
        foreach ($registrations as $registration) {
            $registration->register_id = 'cca-A' . str_pad($registration->id, 5, '0', STR_PAD_LEFT);
            $registration->save();
            $bar->advance();
        }
        
        $bar->finish();
        $this->newLine();
        $this->info('Register IDs updated successfully!');
        
        return 0;
    }
}
