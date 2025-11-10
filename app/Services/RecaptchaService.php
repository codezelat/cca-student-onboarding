<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    private const VERIFY_ENDPOINT = 'https://www.google.com/recaptcha/api/siteverify';

    public function __construct(
        private readonly ?string $secret = null,
        private readonly ?float $scoreThreshold = null,
        private readonly ?string $expectedAction = null,
        private readonly array $allowedHostnames = []
    ) {
    }

    /**
     * Verify the provided token with Google reCAPTCHA v3.
     *
     * @return array{success: bool, score: float|null, action: string|null, hostname: string|null, errors: array}
     */
    public function verify(?string $token, ?string $actionOverride = null): array
    {
        $secret = $this->secret ?? config('services.recaptcha.secret_key');

        if (empty($token) || empty($secret)) {
            return [
                'success' => false,
                'score' => null,
                'action' => null,
                'hostname' => null,
                'errors' => ['missing-token-or-secret'],
            ];
        }

        $response = Http::asForm()->post(self::VERIFY_ENDPOINT, [
            'secret' => $secret,
            'response' => $token,
        ]);

        if (!$response->successful()) {
            Log::error('reCAPTCHA verification request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'score' => null,
                'action' => null,
                'hostname' => null,
                'errors' => ['verification-request-failed'],
            ];
        }

        $body = $response->json();
        $score = isset($body['score']) ? (float) $body['score'] : null;
        $hostname = $body['hostname'] ?? null;
        $action = $body['action'] ?? null;
        $errors = $body['error-codes'] ?? [];

        $threshold = $this->scoreThreshold ?? (float) config('services.recaptcha.score_threshold', 0.6);
        $expectedAction = $actionOverride ?? $this->expectedAction ?? config('services.recaptcha.expected_action');
        $allowedHosts = !empty($this->allowedHostnames)
            ? $this->allowedHostnames
            : (array) (config('services.recaptcha.allowed_hostnames') ?? []);

        $hostnameValid = empty($allowedHosts) || ($hostname && in_array($hostname, $allowedHosts, true));
        $actionValid = !$expectedAction || $action === $expectedAction;
        $scoreValid = $score === null ? false : $score >= $threshold;
        $googleSuccess = (bool) ($body['success'] ?? false);

        return [
            'success' => $googleSuccess && $hostnameValid && $actionValid && $scoreValid,
            'score' => $score,
            'action' => $action,
            'hostname' => $hostname,
            'errors' => $errors,
        ];
    }
}
