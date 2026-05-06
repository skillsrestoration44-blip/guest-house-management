<?php

namespace App\Services;

use App\Models\CodeSetting;
use Illuminate\Support\Facades\DB;

class CodeGeneratorService
{
    /**
     * Generate the next code for the given code type.
     * Uses a transaction + row-level lock to avoid duplicate codes under concurrency.
     */
    public function next(string $codeType): string
    {
        return DB::transaction(function () use ($codeType) {
            $setting = CodeSetting::lockForUpdate()
                ->where('code_type', $codeType)
                ->first();

            if (!$setting) {
                $setting = CodeSetting::create([
                    'code_type' => $codeType,
                    'prefix' => strtoupper(substr($codeType, 0, 3)),
                    'next_number' => 1,
                    'digit_length' => 6,
                    'example' => strtoupper(substr($codeType, 0, 3)) . '-000001',
                ]);
            }

            $number = $setting->next_number;
            $padded = str_pad((string) $number, $setting->digit_length, '0', STR_PAD_LEFT);
            $code = $setting->prefix . '-' . $padded;

            $setting->forceFill([
                'next_number' => $number + 1,
                'example' => $code,
            ])->save();

            return $code;
        });
    }

    /** Generate `count` codes at once. */
    public function nextMany(string $codeType, int $count): array
    {
        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            $codes[] = $this->next($codeType);
        }
        return $codes;
    }
}
