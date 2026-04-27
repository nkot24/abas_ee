<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Log;

class FakeShippingService
{
    public function isConfigured(): bool
    {
        return true;
    }

    public function registerShipment(Order $order): ?string
    {
        $tracking = 'TEST' . str_pad($order->id, 9, '0', STR_PAD_LEFT) . 'LV';

        $order->update(['tracking_number' => $tracking]);

        Log::info('FakeShipping: generated tracking ' . $tracking . ' for order ' . $order->id);

        return $tracking;
    }
}
