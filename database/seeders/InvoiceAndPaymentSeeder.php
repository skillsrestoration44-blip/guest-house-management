<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Models\PaymentMethod;
use App\Models\Refund;
use App\Models\ServiceCharge;
use App\Models\Stay;
use App\Services\CodeGeneratorService;
use Illuminate\Database\Seeder;

class InvoiceAndPaymentSeeder extends Seeder
{
    public function run(): void
    {
        if (Invoice::query()->exists()) {
            return;
        }

        $codeGen = app(CodeGeneratorService::class);
        $cash = PaymentMethod::where('code', 'CASH')->first();
        $bank = PaymentMethod::where('code', 'BANK')->first();
        $aba  = PaymentMethod::where('code', 'ABA')->first();

        /* One invoice per checked-out OR checked-in stay */
        $stays = Stay::whereIn('status', ['checked_in', 'checked_out'])
            ->orderBy('id')
            ->get();

        foreach ($stays as $stay) {
            $invoice = Invoice::create([
                'branch_id'      => $stay->branch_id,
                'invoice_no'     => $codeGen->next('invoice'),
                'booking_id'     => $stay->booking_id,
                'stay_id'        => $stay->id,
                'guest_id'       => $stay->guest_id,
                'invoice_date'   => $stay->actual_check_in_at?->toDateString() ?? now()->toDateString(),
                'due_date'       => $stay->expected_check_out_at?->toDateString() ?? now()->addDays(7)->toDateString(),
                'deposit_amount' => $stay->deposit_amount,
                'discount_amount'=> 0,
                'tax_amount'     => 0,
                'status'         => 'unpaid',
                'created_by'     => 1,
            ]);

            /* Room item — InvoiceItemObserver will auto-recalc invoice totals */
            InvoiceItem::create([
                'invoice_id'  => $invoice->id,
                'item_type'   => 'room',
                'reference_id'=> $stay->id,
                'description' => "Room charges (stay {$stay->stay_no})",
                'quantity'    => 1,
                'unit_price'  => $stay->room_price,
                'total'       => $stay->room_price,
            ]);

            /* Add one item per service_charge */
            $charges = ServiceCharge::where('stay_id', $stay->id)->get();
            foreach ($charges as $ch) {
                InvoiceItem::create([
                    'invoice_id'  => $invoice->id,
                    'item_type'   => 'service',
                    'reference_id'=> $ch->id,
                    'description' => $ch->service?->name ?? 'Service charge',
                    'quantity'    => $ch->quantity,
                    'unit_price'  => $ch->unit_price,
                    'total'       => $ch->total,
                ]);
            }
        }

        /* Payments — observers auto-recalculate invoices and create receipts */
        $invoices = Invoice::orderBy('id')->get();
        $methods = [$cash, $bank, $aba];
        foreach ($invoices as $i => $invoice) {
            $stay = $invoice->stay;
            $method = $methods[$i % count($methods)] ?? $cash;
            if (!$method) {
                continue;
            }

            /* Deposit paid at booking time (if any) */
            if ((float) $invoice->deposit_amount > 0) {
                Payment::create([
                    'branch_id'         => $invoice->branch_id,
                    'invoice_id'        => $invoice->id,
                    'booking_id'        => $invoice->booking_id,
                    'stay_id'           => $invoice->stay_id,
                    'guest_id'          => $invoice->guest_id,
                    'payment_date'      => $invoice->invoice_date,
                    'payment_method_id' => $method->id,
                    'payment_type'      => 'deposit',
                    'amount'            => $invoice->deposit_amount,
                    'reference_no'      => 'DEP-SEED-' . $invoice->id,
                    'status'            => 'completed',
                    'note'              => 'Booking deposit',
                ]);
            }

            /* Final settlement only for completed (checked_out) stays */
            if ($stay && $stay->status === 'checked_out') {
                $invoice->refresh();
                $balance = (float) $invoice->balance_due;
                if ($balance > 0) {
                    Payment::create([
                        'branch_id'         => $invoice->branch_id,
                        'invoice_id'        => $invoice->id,
                        'booking_id'        => $invoice->booking_id,
                        'stay_id'           => $invoice->stay_id,
                        'guest_id'          => $invoice->guest_id,
                        'payment_date'      => $stay->actual_check_out_at,
                        'payment_method_id' => $method->id,
                        'payment_type'      => 'full',
                        'amount'            => $balance,
                        'reference_no'      => 'PAY-SEED-' . $invoice->id,
                        'status'            => 'completed',
                        'note'              => 'Final settlement',
                    ]);
                }
            }
        }

        /* One refund example on the very first checked-out stay */
        $firstClosedInvoice = Invoice::where('status', 'paid')->orderBy('id')->first();
        if ($firstClosedInvoice) {
            $payment = Payment::where('invoice_id', $firstClosedInvoice->id)->orderBy('id')->first();
            if ($payment) {
                Refund::firstOrCreate(
                    ['refund_no' => $codeGen->next('refund')],
                    [
                        'branch_id'   => $firstClosedInvoice->branch_id,
                        'payment_id'  => $payment->id,
                        'invoice_id'  => $firstClosedInvoice->id,
                        'guest_id'    => $firstClosedInvoice->guest_id,
                        'amount'      => 5,
                        'reason'      => 'Mini-bar charge dispute (goodwill refund)',
                        'refunded_at' => now()->subDays(5),
                        'refunded_by' => 1,
                        'status'      => 'completed',
                    ]
                );
            }
        }
    }
}
