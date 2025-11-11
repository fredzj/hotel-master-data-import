<?php

namespace App\Console\Commands;

use App\Services\PmsAdapters\MewsAdapter;
use Illuminate\Console\Command;

class ImportMewsData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mews:import 
                           {--enterprises : Import enterprises only}
                           {--companies : Import companies only}
                           {--services : Import services only}
                           {--categories : Import resource categories only}
                           {--resources : Import resources only}
                           {--features : Import resource features only}
                           {--all : Import all data (default)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import data from Mews PMS API';

    private MewsAdapter $adapter;

    public function __construct(MewsAdapter $adapter)
    {
        parent::__construct();
        $this->adapter = $adapter;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting Mews data import...');

        // Test connection first
        if (!$this->adapter->testConnection()) {
            $this->error('Failed to connect to Mews API. Please check your credentials.');
            return 1;
        }

        $this->info('✓ Connected to Mews API successfully');

        try {
            $importAll = $this->option('all') || (!$this->option('enterprises') 
                && !$this->option('companies') && !$this->option('services') 
                && !$this->option('categories') && !$this->option('resources') 
                && !$this->option('features'));

            if ($this->option('enterprises') || $importAll) {
                $this->importEnterprises();
            }

            if ($this->option('companies') || $importAll) {
                $this->importCompanies();
            }

            if ($this->option('services') || $this->option('categories') || $importAll) {
                $this->importServicesAndCategories();
            }

            if ($this->option('resources') || $importAll) {
                $this->importResources();
            }

            if ($this->option('features') || $importAll) {
                $this->importFeatures();
            }

            $this->info('✓ Mews data import completed successfully!');
            return 0;

        } catch (\Exception $e) {
            $this->error('Import failed: ' . $e->getMessage());
            return 1;
        }
    }

    private function importEnterprises(): void
    {
        $this->info('Importing enterprises...');
        
        $progressBar = $this->output->createProgressBar();
        $progressBar->start();

        try {
            $count = $this->adapter->importAllHotels();
            $progressBar->finish();
            $this->newLine();
            $this->info("✓ Imported {$count} enterprises");
        } catch (\Exception $e) {
            $progressBar->finish();
            $this->newLine();
            $this->error("Failed to import enterprises: {$e->getMessage()}");
            throw $e;
        }
    }

    private function importCompanies(): void
    {
        $this->info('Importing companies...');
        
        $progressBar = $this->output->createProgressBar();
        $progressBar->start();

        try {
            // Check if MewsAdapter has importCompanies method
            if (!method_exists($this->adapter, 'importCompanies')) {
                $progressBar->finish();
                $this->newLine();
                $this->warn('Companies import method not yet implemented in MewsAdapter');
                return;
            }
            
            $count = $this->adapter->importCompanies();
            $progressBar->finish();
            $this->newLine();
            $this->info("✓ Imported {$count} companies");
        } catch (\Exception $e) {
            $progressBar->finish();
            $this->newLine();
            $this->error("Failed to import companies: {$e->getMessage()}");
            throw $e;
        }
    }

    private function importServicesAndCategories(): void
    {
        $this->info('Importing services and resource categories...');
        
        $progressBar = $this->output->createProgressBar();
        $progressBar->start();

        try {
            $count = $this->adapter->importAllRoomTypes();
            $progressBar->finish();
            $this->newLine();
            $this->info("✓ Imported services and {$count} resource categories");
        } catch (\Exception $e) {
            $progressBar->finish();
            $this->newLine();
            $this->error("Failed to import services and categories: {$e->getMessage()}");
            throw $e;
        }
    }

    private function importResources(): void
    {
        $this->info('Importing resources...');
        
        $progressBar = $this->output->createProgressBar();
        $progressBar->start();

        try {
            $count = $this->adapter->importAllRooms();
            $progressBar->finish();
            $this->newLine();
            $this->info("✓ Imported {$count} resources");
        } catch (\Exception $e) {
            $progressBar->finish();
            $this->newLine();
            $this->error("Failed to import resources: {$e->getMessage()}");
            throw $e;
        }
    }

    private function importFeatures(): void
    {
        $this->info('Importing resource features...');
        
        $progressBar = $this->output->createProgressBar();
        $progressBar->start();

        try {
            $count = $this->adapter->importRoomAttributes();
            $progressBar->finish();
            $this->newLine();
            $this->info("✓ Imported {$count} resource features");
        } catch (\Exception $e) {
            $progressBar->finish();
            $this->newLine();
            $this->error("Failed to import features: {$e->getMessage()}");
            throw $e;
        }
    }
}
