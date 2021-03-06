<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class OperationDay extends Model
{
    public $incrementing = false;

    protected $fillable = [
        'day', 'has_operation',
    ];

    public function setDayAttribute($value) {
        $this->attributes['day'] = ucfirst($value);
    }

    public function merchandises() {
        return $this->belongsToMany(Merchandise::class, 'merchandise_operation_day')->withTimestamps();
    }

    public static function json($index = null) {
        $path = resource_path('assets/json/operation_days.json');
        $operation_days = collect(json_decode(file_get_contents($path), true));

        if (is_null($index)) {
            return $operation_days->all();
        }
        return $operation_days[$index]['id'];
    }

    public static function hasOperation($boolean) {
        return self::where('has_operation', $boolean)->get();
    }

    public static function purchaseDates() {
        return DB::table('merchandise_purchase')->select(DB::raw('date(created_at) as date'))->groupBy('date')->orderBy('date', 'asc')->get();
    }
}
