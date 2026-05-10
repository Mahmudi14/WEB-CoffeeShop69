<?php

namespace App\Services;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class ActivityLogService
{
    public function log(
        User $actor,
        string $module,
        string $action,
        string $description,
        Model $subject,
        array $properties = [],
        ?Request $request = null
    ): ActivityLog {
        $request ??= request();

        return ActivityLog::create([
            'user_id' => $actor->id,
            'module' => $module,
            'action' => $action,
            'description' => $description,
            'subject_type' => $subject::class,
            'subject_id' => $subject->getKey(),
            'properties' => $properties,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }
}