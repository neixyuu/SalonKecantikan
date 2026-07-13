<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'title',
        'content',
        'image',
        'video_url',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function getImageUrlAttribute(): ?string
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        return null;
    }

    public function getEmbedVideoUrlAttribute(): ?string
    {
        if (!$this->video_url) return null;

        // Convert YouTube watch URL to embed URL
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $this->video_url, $matches)) {
            return 'https://www.youtube.com/embed/' . $matches[1];
        }

        return $this->video_url;
    }
}
