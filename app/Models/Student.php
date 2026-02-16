<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Str;

class Student extends Model
{
    protected $fillable = [
        'program_id',
        'year_id',
        'name',
        'roll_no',
        'reference_no',
    ];

    public function getRouteKeyName(): string
    {
        return 'qr_token';
    }

    protected static function booted(): void
    {
        static::creating(function (Student $student) {
            if (!$student->qr_token) {
                $student->qr_token = Str::random(40);
            }
        });
    }

    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class);
    }

    public function year(): BelongsTo
    {
        return $this->belongsTo(Year::class);
    }

    public function result(): HasOne
    {
        return $this->hasOne(Result::class);
    }

    public function qrUrl(): string
    {
        return route('public.scan', $this);
    }

    public function qrImageUrl(): string
    {
        $encoded = urlencode($this->qrUrl());
        return "https://api.qrserver.com/v1/create-qr-code/?size=220x220&data={$encoded}";
    }
}
