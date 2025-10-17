<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

class MakeServiceCommand extends Command
{
    protected $signature = 'make:service {name}';
    protected $description = 'Create a new service class in the app/Services directory';

    public function handle()
    {
        $name = $this->argument('name');
        $serviceName = Str::studly($name);
        $servicePath = app_path("Services/{$serviceName}.php");

        if (file_exists($servicePath)) {
            $this->error("Service {$serviceName} already exists!");
            return;
        }

        $stub = "<?php\n\nnamespace App\Services;\n\nclass {$serviceName}\n{\n    // Service logic goes here\n}\n";
        
        file_put_contents($servicePath, $stub);
        $this->info("Service {$serviceName} created successfully in app/Services.");
    }
}