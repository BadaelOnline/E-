<?php

namespace App\Http\Controllers\Commands;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Artisan;

class Command extends Controller
{
    public function migrateDatabase(){
        Artisan::call('migrate');
    }
}
