<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

class ActivityLogService
{
    /**
     * Log an activity to the database.
     *
     * @param string $action           Short identifier, e.g. 'created_order', 'updated_receipt'
     * @param string $description      Human readable description.
     * @param string $actionType       Category: 'inventory', 'order', 'system', etc.
     * @param Model|null $subject      The model being tracked (e.g. Order, GoodsReceipt).
     * @param array $properties        JSON changes or extra data.
     */
    public static function log(
        string $action, 
        string $description, 
        string $actionType = 'system', 
        ?Model $subject = null, 
        array $properties = []
    ) {
        // Automatically add audit metadata if request is available
        if (request()) {
            $properties['ip'] = request()->ip();
            $properties['user_agent'] = request()->userAgent();
        }

        return ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
            'action_type' => $actionType,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject ? $subject->getKey() : null,
            'properties' => empty($properties) ? null : $properties,
        ]);
    }
}
