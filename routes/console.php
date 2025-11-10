<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Medical Platform Custom Commands
Artisan::command('tabib:setup', function () {
    $this->info('🏥 Setting up Tabib Medical Education Platform...');
    
    $this->call('migrate:fresh');
    $this->call('db:seed');
    $this->call('storage:link');
    
    $this->info('✅ Tabib platform setup completed successfully!');
})->purpose('Setup the Tabib medical education platform');

Artisan::command('tabib:stats', function () {
    $this->info('📊 Tabib Platform Statistics:');
    $this->line('Users: Coming soon...');
    $this->line('Courses: Coming soon...');
    $this->line('Questions: Coming soon...');
    $this->line('Quiz Attempts: Coming soon...');
})->purpose('Display platform statistics');
