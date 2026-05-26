<?php

namespace App\Console\Commands;

use App\Models\Order;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class AnonymizeOldOrders extends Command
{
    protected $signature   = 'gdpr:anonymize-old-orders';
    protected $description = 'Anonymize PII on orders older than 2 years (GDPR data retention)';

    public function handle(): void
    {
        $cutoff = now()->subYears(2);

        $count = Order::where('created_at', '<', $cutoff)
            ->where('anonymized', false)
            ->update([
                'first_name'   => 'Deleted',
                'last_name'    => 'User',
                'email'        => 'deleted@deleted.invalid',
                'phone'        => '000',
                'address'      => 'Deleted',
                'city'         => 'Deleted',
                'postal_code'  => '000',
                'anonymized'   => true,
            ]);

        Log::info("GDPR: anonymized {$count} orders older than 2 years");
        $this->info("Anonymized {$count} orders.");
    }
}
