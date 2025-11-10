<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PmsAdapters\ApaleoAdapter;
use App\Models\ApaleoProperty;
use App\Models\ApaleoUnitGroup;
use App\Models\ApaleoUnit;

class ImportApaleoUnits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'apaleo:import-units 
                            {--force : Force import even if data exists}
                            {--property= : Import units for specific property only}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import units (rooms) from Apaleo API for all properties';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting Apaleo units import...');
        
        // Show some stats first
        $existingUnitGroups = ApaleoUnitGroup::count();
        $existingUnits = ApaleoUnit::count();
        $this->info("Current data: {$existingUnitGroups} unit groups, {$existingUnits} units");
        
        if (($existingUnitGroups > 0 || $existingUnits > 0) && !$this->option('force')) {
            if (!$this->confirm('Units already exist. Continue?')) {
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
            
            // Get properties to import units for
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
            
            $totalUnitGroups = 0;
            $totalUnits = 0;
            
            foreach ($properties as $property) {
                $this->info("Processing property: {$property->name} ({$property->apaleo_id})");
                
                // Import unit groups first
                $this->line("  Importing unit groups...");
                $unitGroups = $adapter->importRoomTypes($property->apaleo_id);
                $totalUnitGroups += count($unitGroups);
                $this->info("  Imported " . count($unitGroups) . " unit groups");
                
                // Import units
                $this->line("  Importing units...");
                $units = $adapter->importRooms($property->apaleo_id);
                $totalUnits += count($units);
                $this->info("  Imported " . count($units) . " units");
            }
            
            // Display results
            $this->newLine();
            $this->info('Import completed successfully!');
            $this->table([
                'Metric',
                'Value'
            ], [
                ['Properties Processed', $properties->count()],
                ['Unit Groups Imported/Updated', $totalUnitGroups],
                ['Units Imported/Updated', $totalUnits],
            ]);

            // Show final stats
            $newUnitGroups = ApaleoUnitGroup::count();
            $newUnits = ApaleoUnit::count();
            
            $this->newLine();
            $this->info("Final data: {$newUnitGroups} unit groups (+". ($newUnitGroups - $existingUnitGroups) ."), {$newUnits} units (+". ($newUnits - $existingUnits) .")");
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error('Import failed: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
