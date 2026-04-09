<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemAuditLog extends Model
{
    use HasFactory;

    protected $table = 'system_audit_logs';
    protected $primaryKey = 'log_id';
    protected $fillable = [
        'admin_id',
        'action_type',
        'target_table',
        'target_row_id',
        'previous_value_snapshot',
        'new_value_snapshot',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id', 'user_id');
    }
}
