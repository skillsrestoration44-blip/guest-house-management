<?php

namespace Database\Seeders;

use App\Models\Branch;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\PaymentMethod;
use App\Models\Salary;
use App\Models\Staff;
use App\Services\CodeGeneratorService;
use Illuminate\Database\Seeder;

class AccountingSeeder extends Seeder
{
    public function run(): void
    {
        $codeGen = app(CodeGeneratorService::class);
        $cash = PaymentMethod::where('code', 'CASH')->first();
        $bank = PaymentMethod::where('code', 'BANK')->first();
        $branches = Branch::pluck('id', 'code');

        $categories = [
            ['Utilities',            'Electricity, water, internet'],
            ['Salaries & Wages',     'Staff salaries and wages'],
            ['Maintenance',          'Building, equipment maintenance'],
            ['Cleaning & Laundry',   'Outsourced cleaning, laundry'],
            ['Food & Beverage',      'F&B inventory and supplies'],
            ['Office Supplies',      'Stationery and consumables'],
            ['Marketing',            'Online ads, signage, promotion'],
            ['Other',                'Miscellaneous'],
        ];
        $createdCats = [];
        foreach ($categories as [$name, $desc]) {
            $createdCats[$name] = ExpenseCategory::firstOrCreate(
                ['name' => $name],
                ['description' => $desc, 'status' => 'active']
            );
        }

        $expenses = [
            ['Utilities',          'Electricity bill — Main Branch (last month)',  450, 'paid',     'BR-MAIN'],
            ['Utilities',          'Internet — Main Branch (monthly)',              35, 'paid',     'BR-MAIN'],
            ['Utilities',          'Water bill — Battambang Branch',                42, 'approved', 'BR-BB'],
            ['Cleaning & Laundry', 'Outsourced laundry — last week',               180, 'paid',     'BR-MAIN'],
            ['Maintenance',        'Air-con servicing (3 units)',                  120, 'pending',  'BR-MAIN'],
            ['Marketing',          'Facebook ads — May campaign',                  200, 'approved', 'BR-MAIN'],
            ['Office Supplies',    'A4 paper, printer ink',                         55, 'paid',     'BR-MAIN'],
            ['Food & Beverage',    'Breakfast restock — bakery & dairy',           320, 'paid',     'BR-SR'],
            ['Other',              'Bank transaction fees (May)',                   18, 'paid',     'BR-MAIN'],
        ];

        foreach ($expenses as [$cat, $desc, $amt, $status, $branchCode]) {
            Expense::firstOrCreate(
                ['description' => $desc, 'expense_date' => now()->subDays(15)->toDateString()],
                [
                    'branch_id'           => $branches[$branchCode] ?? null,
                    'expense_no'          => $codeGen->next('expense'),
                    'expense_category_id' => $createdCats[$cat]->id,
                    'amount'              => $amt,
                    'payment_method_id'   => ($cat === 'Salaries & Wages' ? $bank?->id : $cash?->id),
                    'reference_no'        => null,
                    'attachment'          => null,
                    'status'              => $status,
                    'created_by'          => 1,
                    'approved_by'         => $status !== 'pending' ? 1 : null,
                    'approved_at'         => $status !== 'pending' ? now()->subDays(10) : null,
                ]
            );
        }

        /* Salaries: last 2 months for every active staff member */
        $staff = Staff::where('status', 'active')->where('salary', '>', 0)->get();
        foreach ([now()->subMonths(2), now()->subMonth()] as $month) {
            $month_str = $month->format('Y-m');
            foreach ($staff as $s) {
                $basic = (float) $s->salary;
                $bonus = $month->equalTo(now()->subMonth()) ? 25 : 0;
                $deduction = 0;
                Salary::firstOrCreate(
                    ['staff_id' => $s->id, 'salary_month' => $month_str],
                    [
                        'branch_id'         => $s->branch_id,
                        'basic_salary'      => $basic,
                        'bonus'             => $bonus,
                        'deduction'         => $deduction,
                        'net_salary'        => $basic + $bonus - $deduction,
                        'paid_at'           => $month->copy()->endOfMonth(),
                        'payment_method_id' => $bank?->id ?? $cash?->id,
                        'status'            => 'paid',
                        'created_by'        => 1,
                    ]
                );
            }
        }
    }
}
