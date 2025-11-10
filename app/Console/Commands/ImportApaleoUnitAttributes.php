<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PmsAdapters\ApaleoAdapter;
use App\Models\ApaleoProperty;
use App\Models\ApaleoUnitAttribute;

class ImportApaleoUnitAttributes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apaleo:import-unit-attributes 
                            {--force : Force import even if data exists}
                            {--property= : Import attributes for specific property only}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import unit attributes from Apaleo API for all properties';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Apaleo unit attributes import...');
        
        // Show some stats first
        $existingAttributes = ApaleoUnitAttribute::count();
        $this->info("Current data: {$existingAttributes} unit attributes");
        
        if ($existingAttributes > 0 && !$this->option('force')) {
            if (!$this->confirm('Unit attributes already exist. Continue?')) {
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
            
            // Get properties to import attributes for
            if ($this->option('property')) {
                $properties = ApaleoProperty::where('apaleo_id', $this->option('property'))->get();
                if ($properties->isEmpty()) {
                    $this->error('Property not found: ' . $this->option('property'));
                    return Command::FAILURE;
                }
            } else {
                $properties = ApaleoProperty::all();
            }
            
            if ($properties->isEmpty()) {
                $this->error('No properties found. Please import properties first using: php artisan apaleo:import-properties');
                return Command::FAILURE;
            }
            
            $totalAttributes = 0;
            
            foreach ($properties as $property) {
                $this->info("Processing property: {$property->name} ({$property->apaleo_id})");
                
                // Import unit attributes
                $this->line("  Importing unit attributes...");
                $attributes = $adapter->importRoomAttributes($property->apaleo_id);
                $totalAttributes += count($attributes);
                $this->info("  Imported " . count($attributes) . " unit attributes");
            }
            
            // Display results
            $this->newLine();
            $this->info('Import completed successfully!');
            $this->table([
                'Metric',
                'Value'
            ], [
                ['Properties Processed', $properties->count()],
                ['Unit Attributes Imported/Updated', $totalAttributes],
            ]);

            // Show final stats
            $newAttributes = ApaleoUnitAttribute::count();
            
            $this->newLine();
            $this->info("Final data: {$newAttributes} unit attributes (+". ($newAttributes - $existingAttributes) .")");
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error('Import failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
