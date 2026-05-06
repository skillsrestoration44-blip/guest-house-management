<?php

namespace App\Observers;

use App\Models\InvoiceItem;

class InvoiceItemObserver
{
    public function saving(InvoiceItem $item): void
    {
        $item->total = round((float) $item->quantity * (float) $item->unit_price, 2);
    }

    public function saved(InvoiceItem $item): void
    {
        if ($invoice = $item->invoice) {
            $invoice->recalculate();
        }
    }

    public function deleted(InvoiceItem $item): void
    {
        if ($invoice = $item->invoice) {
            $invoice->recalculate();
        }
    }
}
