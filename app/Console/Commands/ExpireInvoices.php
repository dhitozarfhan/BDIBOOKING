<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use Illuminate\Console\Command;

class ExpireInvoices extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'invoices:expire';

    /**
     * The console command description.
     */
    protected $description = 'Mark unpaid invoices past their due date as expired';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $count = Invoice::where('status', 'unpaid')
            ->where('due_date', '<', now())
            ->update(['status' => 'expired']);

        $this->info("Expired {$count} invoice(s).");

        return self::SUCCESS;
    }
}
