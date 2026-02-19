<?php

namespace App\Services;

use App\Models\AdminActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ActivityLogger
{
    /**
     * Write an admin activity event.
     *
     * @param  array<string, mixed>  $context
     */
    public function log(string $action, array $context = []): void
    {
        try {
            $request = request();
            $actor = $this->resolveActor($context['actor'] ?? null);

            [$subjectType, $subjectId, $subjectLabel] = $this->extractSubject($context);

            AdminActivityLog::create([
                'actor_user_id' => $actor?->id,
                'actor_name_snapshot' => $actor?->name,
                'actor_email_snapshot' => $actor?->email,
                'category' => (string) ($context['category'] ?? 'general'),
                'action' => $action,
                'status' => (string) ($context['status'] ?? 'success'),
                'subject_type' => $subjectType,
                'subject_id' => $subjectId,
                'subject_label' => $subjectLabel,
                'message' => isset($context['message']) ? (string) $context['message'] : null,
                'route_name' => $request instanceof Request ? $request->route()?->getName() : null,
                'http_method' => $request instanceof Request ? $request->method() : null,
                'ip_address' => $request instanceof Request ? $request->ip() : null,
                'user_agent' => $request instanceof Request ? $request->userAgent() : null,
                'request_id' => $this->resolveRequestId($request),
                'before_data' => $this->sanitizePayload($context['before'] ?? null),
                'after_data' => $this->sanitizePayload($context['after'] ?? null),
                'meta' => $this->sanitizePayload($context['meta'] ?? null),
            ]);
        } catch (\Throwable $e) {
            // Logging failures must never break primary actions.
        }
    }

    /**
     * @param  mixed  $actor
     */
    private function resolveActor(mixed $actor): ?User
    {
        if ($actor instanceof User) {
            return $actor;
        }

        $user = Auth::guard('admin')->user();

        return $user instanceof User ? $user : null;
    }

    /**
     * @param  array<string, mixed>  $context
     * @return array{0: ?string, 1: ?int, 2: ?string}
     */
    private function extractSubject(array $context): array
    {
        if (($context['subject'] ?? null) instanceof Model) {
            /** @var Model $subject */
            $subject = $context['subject'];

            return [
                (string) ($context['subject_type'] ?? Str::snake(class_basename($subject))),
                (int) $subject->getKey(),
                (string) ($context['subject_label'] ?? $this->subjectLabelFromModel($subject)),
            ];
        }

        return [
            isset($context['subject_type']) ? (string) $context['subject_type'] : null,
            isset($context['subject_id']) ? (int) $context['subject_id'] : null,
            isset($context['subject_label']) ? (string) $context['subject_label'] : null,
        ];
    }

    private function subjectLabelFromModel(Model $subject): string
    {
        foreach (['name', 'full_name', 'register_id', 'code', 'email'] as $field) {
            $value = $subject->getAttribute($field);
            if (is_string($value) && $value !== '') {
                return $value;
            }
        }

        return class_basename($subject) . '#' . $subject->getKey();
    }

    private function resolveRequestId(?Request $request): ?string
    {
        if (! $request) {
            return null;
        }

        $existing = $request->attributes->get('request_id');
        if (is_string($existing) && $existing !== '') {
            return $existing;
        }

        $fromHeader = $request->header('X-Request-Id');
        if (is_string($fromHeader) && $fromHeader !== '') {
            $request->attributes->set('request_id', $fromHeader);
            return $fromHeader;
        }

        $generated = (string) Str::uuid();
        $request->attributes->set('request_id', $generated);

        return $generated;
    }

    /**
     * @param  mixed  $payload
     * @return array<string, mixed>|null
     */
    private function sanitizePayload(mixed $payload): ?array
    {
        if ($payload === null) {
            return null;
        }

        if ($payload instanceof Model) {
            $payload = $payload->toArray();
        }

        if (! is_array($payload)) {
            return ['value' => $payload];
        }

        return $this->sanitizeArray($payload);
    }

    /**
     * @param  array<string|int, mixed>  $data
     * @return array<string, mixed>
     */
    private function sanitizeArray(array $data): array
    {
        $sanitized = [];

        foreach ($data as $key => $value) {
            $keyString = is_string($key) ? strtolower($key) : (string) $key;

            if ($this->isSensitiveKey($keyString)) {
                $sanitized[(string) $key] = '***';
                continue;
            }

            if (is_array($value)) {
                $sanitized[(string) $key] = $this->sanitizeArray($value);
                continue;
            }

            $sanitized[(string) $key] = $value;
        }

        return $sanitized;
    }

    private function isSensitiveKey(string $key): bool
    {
        return str_contains($key, 'password')
            || str_contains($key, 'token')
            || str_contains($key, 'secret')
            || str_contains($key, 'remember');
    }
}
