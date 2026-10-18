<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AuditLogService
{
    /**
     * Record an audit event into audit_logs table.
     *
     * @param string $action LOGIN, LOGIN_FAILED, LOGOUT, CREATE, UPDATE, DELETE, APPROVE, UNPUBLISH
     * @param string $module auth, news, tenders, vacancies, services, projects, resources, gallery, destinations, staff, settings
     * @param string $description Human-readable description of what was done
     * @param mixed $user User model or user object (optional, defaults to request()->user())
     * @param string|int|null $recordId ID of touched entity
     * @param array|null $changes Optional payload / old/new changes
     * @param Request|null $request
     */
    public static function log(
        string $action,
        string $module,
        string $description,
        $user = null,
        $recordId = null,
        ?array $changes = null,
        ?Request $request = null
    ): void {
        try {
            $req = $request ?: request();
            $resolvedUser = $user ?: ($req ? $req->user() : null);

            $ip = $req ? ($req->header('X-Forwarded-For') ?: $req->ip()) : null;
            if ($ip && str_contains($ip, ',')) {
                $ip = trim(explode(',', $ip)[0]);
            }

            $userAgent = $req ? substr((string)$req->userAgent(), 0, 500) : null;

            DB::table('audit_logs')->insert([
                'user_id' => $resolvedUser ? $resolvedUser->id : null,
                'user_name' => $resolvedUser ? $resolvedUser->name : 'System / Guest',
                'user_email' => $resolvedUser ? $resolvedUser->email : null,
                'user_role' => $resolvedUser ? ($resolvedUser->role ?? null) : null,
                'action' => strtoupper($action),
                'module' => strtolower($module),
                'record_id' => $recordId ? (string)$recordId : null,
                'description' => $description,
                'changes' => $changes ? json_encode($changes) : null,
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            // Never break application flow if logging encounters a minor exception
            logger()->error('AuditLogService Error: ' . $e->getMessage());
        }
    }
}
