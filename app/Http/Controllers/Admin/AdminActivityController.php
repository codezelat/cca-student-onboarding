<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivityLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminActivityController extends Controller
{
    /**
     * Show activity timeline with filters.
     */
    public function index(Request $request)
    {
        $filters = $this->validatedFilters($request);
        $query = AdminActivityLog::query()->with('actor')->orderByDesc('created_at');

        if (! empty($filters['from_date'])) {
            $query->where('created_at', '>=', Carbon::parse((string) $filters['from_date'])->startOfDay());
        }

        if (! empty($filters['to_date'])) {
            $query->where('created_at', '<=', Carbon::parse((string) $filters['to_date'])->endOfDay());
        }

        if (! empty($filters['actor_user_id'])) {
            $query->where('actor_user_id', (int) $filters['actor_user_id']);
        }

        if (! empty($filters['category'])) {
            $query->where('category', (string) $filters['category']);
        }

        if (! empty($filters['action'])) {
            $query->where('action', (string) $filters['action']);
        }

        if (! empty($filters['status'])) {
            $query->where('status', (string) $filters['status']);
        }

        if (! empty($filters['subject_type'])) {
            $query->where('subject_type', (string) $filters['subject_type']);
        }

        if (! empty($filters['search'])) {
            $term = (string) $filters['search'];
            $query->where(function ($q) use ($term): void {
                $q->where('message', 'like', '%' . $term . '%')
                    ->orWhere('subject_label', 'like', '%' . $term . '%')
                    ->orWhere('action', 'like', '%' . $term . '%')
                    ->orWhere('route_name', 'like', '%' . $term . '%')
                    ->orWhere('request_id', 'like', '%' . $term . '%');
            });
        }

        $activities = $query->paginate(30)->appends($filters);

        $actors = User::withTrashed()
            ->whereIn('id', AdminActivityLog::query()->select('actor_user_id')->whereNotNull('actor_user_id')->distinct())
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        $categories = AdminActivityLog::query()
            ->select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $actions = AdminActivityLog::query()
            ->select('action')
            ->distinct()
            ->orderBy('action')
            ->pluck('action');

        $subjectTypes = AdminActivityLog::query()
            ->select('subject_type')
            ->whereNotNull('subject_type')
            ->distinct()
            ->orderBy('subject_type')
            ->pluck('subject_type');

        return view('admin.activity.index', [
            'activities' => $activities,
            'actors' => $actors,
            'categories' => $categories,
            'actions' => $actions,
            'subjectTypes' => $subjectTypes,
        ]);
    }

    /**
     * Show activity detail.
     */
    public function show(int $id)
    {
        $activity = AdminActivityLog::with('actor')->findOrFail($id);

        return view('admin.activity.show', compact('activity'));
    }

    /**
     * Export filtered activity timeline to CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        $filters = $this->validatedFilters($request);
        $query = AdminActivityLog::query()->with('actor')->orderByDesc('created_at');

        if (! empty($filters['from_date'])) {
            $query->where('created_at', '>=', Carbon::parse((string) $filters['from_date'])->startOfDay());
        }

        if (! empty($filters['to_date'])) {
            $query->where('created_at', '<=', Carbon::parse((string) $filters['to_date'])->endOfDay());
        }

        foreach (['actor_user_id', 'category', 'action', 'status', 'subject_type'] as $field) {
            if (! empty($filters[$field])) {
                $query->where($field, (string) $filters[$field]);
            }
        }

        if (! empty($filters['search'])) {
            $term = (string) $filters['search'];
            $query->where(function ($q) use ($term): void {
                $q->where('message', 'like', '%' . $term . '%')
                    ->orWhere('subject_label', 'like', '%' . $term . '%')
                    ->orWhere('action', 'like', '%' . $term . '%')
                    ->orWhere('route_name', 'like', '%' . $term . '%')
                    ->orWhere('request_id', 'like', '%' . $term . '%');
            });
        }

        $filename = 'admin_activity_' . now()->format('Y-m-d_His') . '.csv';

        return response()->stream(function () use ($query): void {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'Date Time',
                'Actor',
                'Category',
                'Action',
                'Status',
                'Subject Type',
                'Subject ID',
                'Subject Label',
                'Message',
                'Route',
                'Method',
                'IP',
                'Request ID',
                'Before Data',
                'After Data',
                'Meta',
            ]);

            $query->chunk(500, function (Collection $rows) use ($file): void {
                foreach ($rows as $row) {
                    fputcsv($file, [
                        $row->created_at?->format('Y-m-d H:i:s'),
                        $row->actor_name_snapshot ?: ($row->actor_email_snapshot ?: 'System'),
                        $row->category,
                        $row->action,
                        $row->status,
                        $row->subject_type,
                        $row->subject_id,
                        $row->subject_label,
                        $row->message,
                        $row->route_name,
                        $row->http_method,
                        $row->ip_address,
                        $row->request_id,
                        $row->before_data ? json_encode($row->before_data, JSON_UNESCAPED_UNICODE) : null,
                        $row->after_data ? json_encode($row->after_data, JSON_UNESCAPED_UNICODE) : null,
                        $row->meta ? json_encode($row->meta, JSON_UNESCAPED_UNICODE) : null,
                    ]);
                }
            });

            fclose($file);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedFilters(Request $request): array
    {
        return $request->validate([
            'from_date' => ['nullable', 'date'],
            'to_date' => ['nullable', 'date', 'after_or_equal:from_date'],
            'actor_user_id' => ['nullable', 'integer', 'exists:users,id'],
            'category' => ['nullable', 'string', 'max:50'],
            'action' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'string', 'in:success,failed'],
            'subject_type' => ['nullable', 'string', 'max:80'],
            'search' => ['nullable', 'string', 'max:255'],
        ]);
    }
}
