<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // Tambah status 'cancelled' — karena kolom sudah string enum, tidak perlu modify
            // Hanya tambah kolom baru
            $table->timestamp('cancel_requested_at')->nullable()->after('rejection_reason');
            $table->date('reschedule_requested_date')->nullable()->after('cancel_requested_at');
            $table->time('reschedule_requested_time')->nullable()->after('reschedule_requested_date');
            $table->text('cancellation_reason')->nullable()->after('reschedule_requested_time');
        });
    }

    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn([
                'cancel_requested_at',
                'reschedule_requested_date',
                'reschedule_requested_time',
                'cancellation_reason',
            ]);
        });
    }
};
