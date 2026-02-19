<?php

namespace App\Services;

use App\Models\Program;
use App\Models\ProgramIntakeWindow;
use Carbon\CarbonInterface;
use Illuminate\Support\Collection;

class ProgramCatalogService
{
    /**
     * Programs keyed by code for student form consumption.
     *
     * @return array<string, array{name: string, year: string, duration: string, active: bool, price: float, currency: string}>
     */
    public function formCatalog(?CarbonInterface $at = null): array
    {
        $at ??= now();

        $catalog = [];
        $programs = Program::with(['intakeWindows' => function ($query) use ($at): void {
            $query->where('is_active', true)
                ->where('opens_at', '<=', $at)
                ->where('closes_at', '>=', $at)
                ->orderBy('opens_at');
        }])->orderBy('display_order')->orderBy('code')->get();

        foreach ($programs as $program) {
            $currentWindow = $program->intakeWindows->first();
            $isOpen = $program->is_active && $currentWindow !== null;
            $price = $this->effectivePrice($program, $currentWindow);

            $catalog[$program->code] = [
                'name' => $program->name,
                'year' => $program->year_label,
                'duration' => $program->duration_label,
                'active' => $isOpen,
                'price' => $price,
                'currency' => $program->currency,
            ];
        }

        return $catalog;
    }

    /**
     * Program options keyed by code for admin forms.
     *
     * @return array<string, array{name: string, year: string, duration: string, active: bool}>
     */
    public function adminProgramOptions(): array
    {
        return Program::orderBy('display_order')
            ->orderBy('code')
            ->get()
            ->mapWithKeys(fn (Program $program) => [
                $program->code => [
                    'name' => $program->name,
                    'year' => $program->year_label,
                    'duration' => $program->duration_label,
                    'active' => $program->is_active,
                ],
            ])->all();
    }

    public function findProgramByCode(string $code): ?Program
    {
        return Program::where('code', strtoupper(trim($code)))->first();
    }

    public function validateProgramCodeFormat(string $code): bool
    {
        return preg_match('/^[A-Z]{3}-[A-Z]{2,4}[0-9]{2}$/', strtoupper(trim($code))) === 1;
    }

    public function currentIntakeWindow(Program $program, ?CarbonInterface $at = null): ?ProgramIntakeWindow
    {
        $at ??= now();

        return $program->intakeWindows()
            ->where('is_active', true)
            ->where('opens_at', '<=', $at)
            ->where('closes_at', '>=', $at)
            ->orderBy('opens_at')
            ->first();
    }

    public function isOpenForRegistration(Program $program, ?CarbonInterface $at = null): bool
    {
        if (! $program->is_active) {
            return false;
        }

        return $this->currentIntakeWindow($program, $at) !== null;
    }

    public function effectivePrice(Program $program, ?ProgramIntakeWindow $window = null): float
    {
        if ($window && $window->price_override !== null) {
            return (float) $window->price_override;
        }

        return (float) ($program->base_price ?? 0);
    }

    /**
     * Find active windows that overlap with proposed range.
     *
     * @return Collection<int, ProgramIntakeWindow>
     */
    public function overlappingWindows(
        Program $program,
        CarbonInterface $opensAt,
        CarbonInterface $closesAt,
        ?int $ignoreWindowId = null
    ): Collection {
        return $program->intakeWindows()
            ->where('is_active', true)
            ->when($ignoreWindowId !== null, fn ($query) => $query->where('id', '!=', $ignoreWindowId))
            ->where(function ($query) use ($opensAt, $closesAt): void {
                $query->where('opens_at', '<', $closesAt)
                    ->where('closes_at', '>', $opensAt);
            })->get();
    }
}
