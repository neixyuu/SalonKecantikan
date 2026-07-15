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
    ];

    protected function casts(): array
    {
        return [
            'schedule_date' => 'date',
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
            'pending'  => '<span class="badge-pending">Menunggu</span>',
            'approved' => '<span class="badge-approved">Disetujui</span>',
            'rejected' => '<span class="badge-rejected">Ditolak</span>',
            default    => '<span class="badge-pending">-</span>',
        };
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->schedule_date->translatedFormat('d F Y');
    }
}
