<?php

namespace App\Observers;

use App\Models\Payment;
use App\Models\Receipt;
use App\Services\CodeGeneratorService;
use Illuminate\Support\Facades\Auth;

class PaymentObserver
{
    public function creating(Payment $payment): void
    {
        if (empty($payment->payment_no)) {
            $payment->payment_no = app(CodeGeneratorService::class)->next('payment');
        }
        if (empty($payment->status)) {
            $payment->status = 'completed';
        }
        if (empty($payment->payment_date)) {
            $payment->payment_date = now();
        }
        if (empty($payment->received_by)) {
            $payment->received_by = Auth::id();
        }
    }

    public function created(Payment $payment): void
    {
        $this->ensureReceipt($payment);
        $this->recalculateInvoice($payment);
    }

    public function updated(Payment $payment): void
    {
        $this->ensureReceipt($payment);
        $this->recalculateInvoice($payment);
    }

    public function deleted(Payment $payment): void
    {
        $this->recalculateInvoice($payment);
    }

    protected function ensureReceipt(Payment $payment): void
    {
        if ($payment->status !== 'completed' || !$payment->invoice_id) {
            return;
        }
        $exists = Receipt::where('payment_id', $payment->id)->exists();
        if ($exists) {
            return;
        }
        Receipt::create([
            'branch_id' => $payment->branch_id,
            'receipt_no' => app(CodeGeneratorService::class)->next('receipt'),
            'payment_id' => $payment->id,
            'invoice_id' => $payment->invoice_id,
            'issued_at' => now(),
            'issued_by' => Auth::id() ?? $payment->received_by,
        ]);
    }

    protected function recalculateInvoice(Payment $payment): void
    {
        if ($payment->invoice_id && $invoice = $payment->invoice) {
            $invoice->recalculate();
        }
    }
}
