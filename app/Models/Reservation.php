<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'treatment_id',
        'schedule_date',
        'schedule_time',
        'status',
        'notes',
        'rejection_reason',
        'cancel_requested_at',
        'reschedule_requested_date',
        'reschedule_requested_time',
        'cancellation_reason',
    ];

    protected function casts(): array
    {
        return [
            'schedule_date'             => 'date',
            'reschedule_requested_date' => 'date',
            'cancel_requested_at'       => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'pending'   => '<span class="badge-pending">Menunggu</span>',
            'approved'  => '<span class="badge-approved">Disetujui</span>',
            'rejected'  => '<span class="badge-rejected">Ditolak</span>',
            'cancelled' => '<span class="badge-rejected">Dibatalkan</span>',
            default     => '<span class="badge-pending">-</span>',
        };
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->schedule_date->translatedFormat('d F Y');
    }

    /** Apakah ada permintaan pembatalan yang belum diproses */
    public function hasPendingCancelRequest(): bool
    {
        return !is_null($this->cancel_requested_at) && $this->status === 'approved';
    }

    /** Apakah ada permintaan reschedule yang belum diproses */
    public function hasPendingRescheduleRequest(): bool
    {
        return !is_null($this->reschedule_requested_date) && $this->status === 'approved';
    }
}
