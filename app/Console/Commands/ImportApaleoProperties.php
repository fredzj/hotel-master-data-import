<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PmsAdapters\ApaleoAdapter;
use App\Models\ApaleoProperty;

class ImportApaleoProperties extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apaleo:import-properties 
                            {--force : Force import even if data exists}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import properties from Apaleo API';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Apaleo properties import...');
        
        // Show some stats first
        $existingProperties = ApaleoProperty::count();
        $this->info("Current data: {$existingProperties} properties");
        
        if ($existingProperties > 0 && !$this->option('force')) {
            if (!$this->confirm('Properties already exist. Continue?')) {
                $this->info('Import cancelled.');
                return Command::SUCCESS;
            }
        }

        try {
            $adapter = new ApaleoAdapter();
            
            $this->info('Authenticating with Apaleo...');
            if (!$adapter->authenticate()) {
                $this->error('Failed to authenticate with Apaleo. Please check your credentials.');
                return Command::FAILURE;
            }
            
            $this->info('Authentication successful. Starting import...');
            
            $properties = $adapter->importHotels();
            
            // Display results
            $this->newLine();
            $this->info('Import completed successfully!');
            $this->table([
                'Metric',
                'Value'
            ], [
                ['Properties Imported/Updated', count($properties)],
            ]);

            // Show final stats
            $newProperties = ApaleoProperty::count();
            $this->info("Final data: {$newProperties} properties (+". ($newProperties - $existingProperties) .")");
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error('Import failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
