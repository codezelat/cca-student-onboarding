<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\ProgramIntakeWindow;
use App\Services\ProgramCatalogService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AdminProgramController extends Controller
{
    public function __construct(private readonly ProgramCatalogService $programCatalogService)
    {
    }

    /**
     * List programs with intake states.
     */
    public function index(): View
    {
        $now = now();
        $programs = Program::with(['intakeWindows' => function ($query): void {
            $query->orderByDesc('opens_at');
        }])->orderBy('display_order')->orderBy('code')->get();

        return view('admin.programs.index', compact('programs', 'now'));
    }

    /**
     * Show create form.
     */
    public function create(): View
    {
        return view('admin.programs.create');
    }

    /**
     * Store a new program.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateProgramData($request, true);
        $adminId = Auth::guard('admin')->id();

        $program = Program::create([
            'code' => strtoupper(trim($validated['code'])),
            'name' => $validated['name'],
            'year_label' => $validated['year_label'],
            'duration_label' => $validated['duration_label'],
            'base_price' => $validated['base_price'] ?? 0,
            'currency' => strtoupper($validated['currency']),
            'display_order' => $validated['display_order'] ?? 0,
            'is_active' => (bool) ($validated['is_active'] ?? false),
            'created_by' => $adminId,
            'updated_by' => $adminId,
        ]);

        return redirect()
            ->route('admin.programs.edit', $program->id)
            ->with('success', 'Program created successfully. Add at least one intake window to open registration.');
    }

    /**
     * Show edit form for program and intake management.
     */
    public function edit(int $programId): View
    {
        $program = Program::with('intakeWindows')->findOrFail($programId);
        $now = now();

        return view('admin.programs.edit', compact('program', 'now'));
    }

    /**
     * Update program details.
     */
    public function update(Request $request, int $programId): RedirectResponse
    {
        $program = Program::findOrFail($programId);
        $validated = $this->validateProgramData($request, false);

        $program->update([
            'name' => $validated['name'],
            'year_label' => $validated['year_label'],
            'duration_label' => $validated['duration_label'],
            'base_price' => $validated['base_price'] ?? 0,
            'currency' => strtoupper($validated['currency']),
            'display_order' => $validated['display_order'] ?? 0,
            'is_active' => (bool) ($validated['is_active'] ?? false),
            'updated_by' => Auth::guard('admin')->id(),
        ]);

        return redirect()
            ->route('admin.programs.edit', $program->id)
            ->with('success', 'Program updated successfully.');
    }

    /**
     * Toggle active status of a program.
     */
    public function toggle(int $programId): RedirectResponse
    {
        $program = Program::findOrFail($programId);

        $program->update([
            'is_active' => ! $program->is_active,
            'updated_by' => Auth::guard('admin')->id(),
        ]);

        return redirect()
            ->route('admin.programs.index')
            ->with('success', 'Program status updated.');
    }

    /**
     * Add an intake window.
     */
    public function storeIntake(Request $request, int $programId): RedirectResponse
    {
        $program = Program::findOrFail($programId);
        $validated = $this->validateIntakeData($request);
        $isActive = (bool) ($validated['is_active'] ?? false);

        if ($isActive) {
            $this->ensureNoActiveOverlap($program, $validated['opens_at'], $validated['closes_at']);
        }

        $program->intakeWindows()->create([
            'window_name' => $validated['window_name'],
            'opens_at' => $validated['opens_at'],
            'closes_at' => $validated['closes_at'],
            'price_override' => $validated['price_override'] ?? null,
            'is_active' => $isActive,
            'created_by' => Auth::guard('admin')->id(),
            'updated_by' => Auth::guard('admin')->id(),
        ]);

        return redirect()
            ->route('admin.programs.edit', $program->id)
            ->with('success', 'Intake window added successfully.');
    }

    /**
     * Edit an intake window.
     */
    public function editIntake(int $programId, int $intakeId): View
    {
        $program = Program::findOrFail($programId);
        $intake = $this->findIntake($program, $intakeId);

        return view('admin.programs.edit-intake', compact('program', 'intake'));
    }

    /**
     * Update intake window.
     */
    public function updateIntake(Request $request, int $programId, int $intakeId): RedirectResponse
    {
        $program = Program::findOrFail($programId);
        $intake = $this->findIntake($program, $intakeId);
        $validated = $this->validateIntakeData($request);
        $isActive = (bool) ($validated['is_active'] ?? false);

        if ($isActive) {
            $this->ensureNoActiveOverlap($program, $validated['opens_at'], $validated['closes_at'], $intake->id);
        }

        $intake->update([
            'window_name' => $validated['window_name'],
            'opens_at' => $validated['opens_at'],
            'closes_at' => $validated['closes_at'],
            'price_override' => $validated['price_override'] ?? null,
            'is_active' => $isActive,
            'updated_by' => Auth::guard('admin')->id(),
        ]);

        return redirect()
            ->route('admin.programs.edit', $program->id)
            ->with('success', 'Intake window updated successfully.');
    }

    /**
     * Toggle intake window status.
     */
    public function toggleIntake(int $programId, int $intakeId): RedirectResponse
    {
        $program = Program::findOrFail($programId);
        $intake = $this->findIntake($program, $intakeId);
        $nextActive = ! $intake->is_active;

        if ($nextActive) {
            $this->ensureNoActiveOverlap(
                $program,
                $intake->opens_at->copy(),
                $intake->closes_at->copy(),
                $intake->id
            );
        }

        $intake->update([
            'is_active' => $nextActive,
            'updated_by' => Auth::guard('admin')->id(),
        ]);

        return redirect()
            ->route('admin.programs.edit', $program->id)
            ->with('success', 'Intake window status updated.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateProgramData(Request $request, bool $isCreate): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'year_label' => 'required|string|max:20',
            'duration_label' => 'required|string|max:50',
            'base_price' => 'nullable|numeric|min:0',
            'currency' => 'required|string|size:3',
            'display_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ];

        if ($isCreate) {
            $rules['code'] = [
                'required',
                'string',
                'max:20',
                'regex:/^[A-Z]{3}-[A-Z]{2,4}[0-9]{2}$/',
                Rule::unique('programs', 'code'),
            ];
        }

        return $request->validate($rules);
    }

    /**
     * @return array<string, mixed>
     */
    private function validateIntakeData(Request $request): array
    {
        return $request->validate([
            'window_name' => 'required|string|max:120',
            'opens_at' => 'required|date',
            'closes_at' => 'required|date|after:opens_at',
            'price_override' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ]);
    }

    private function findIntake(Program $program, int $intakeId): ProgramIntakeWindow
    {
        return ProgramIntakeWindow::where('program_id', $program->id)
            ->where('id', $intakeId)
            ->firstOrFail();
    }

    private function ensureNoActiveOverlap(
        Program $program,
        string|Carbon $opensAt,
        string|Carbon $closesAt,
        ?int $ignoreWindowId = null
    ): void {
        $start = $opensAt instanceof Carbon ? $opensAt : Carbon::parse($opensAt);
        $end = $closesAt instanceof Carbon ? $closesAt : Carbon::parse($closesAt);

        $overlaps = $this->programCatalogService->overlappingWindows(
            $program,
            $start,
            $end,
            $ignoreWindowId
        );

        if ($overlaps->isNotEmpty()) {
            throw ValidationException::withMessages([
                'opens_at' => 'This intake window overlaps with another active intake window for the same program.',
            ]);
        }
    }
}
