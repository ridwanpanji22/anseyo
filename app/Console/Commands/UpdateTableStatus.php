<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Table;
use Illuminate\Console\Command;

class UpdateTableStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tables:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update all table statuses based on active orders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Updating table statuses...');

        $tables = Table::all();
        $updatedCount = 0;

        foreach ($tables as $table) {
            // Check if there are any active orders for this table
            $activeOrders = Order::where('table_id', $table->id)
                ->whereIn('status', ['pending', 'preparing', 'ready', 'served'])
                ->count();

            $newStatus = $activeOrders > 0 ? 'occupied' : 'available';
            
            if ($table->status !== $newStatus) {
                $table->update(['status' => $newStatus]);
                $this->line("Table {$table->number}: {$table->status} → {$newStatus}");
                $updatedCount++;
            } else {
                $this->line("Table {$table->number}: {$table->status} (no change)");
            }
        }

        $this->info("Updated {$updatedCount} table(s).");
        
        return Command::SUCCESS;
    }
}
