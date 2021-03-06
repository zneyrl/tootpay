<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Sofa\Eloquence\Eloquence;

class Category extends Model
{
    use Eloquence;

    protected $searchableColumns = [
        'name', 'description',
    ];

    protected $fillable = [
        'name', 'description', 'manage_inventory'
    ];

    public function merchandises() {
        return $this->hasMany(Merchandise::class);
    }

    public function setManageInventoryAttribute($value) {
        $this->attributes['manage_inventory'] = ($value == 'on') ? 1 : 0;
    }

    public function setNameAttribute($value) {
        $this->attributes['name'] = ucwords(strtolower($value));
    }

    public static function json($index = null) {
        $path = resource_path('assets/json/merchandises/categories.json');
        $categories = collect(json_decode(file_get_contents($path), true));

        if (is_null($index)) {
            return $categories->all();
        }
        return $categories[$index];
    }

    public static function withNumberOfEntries() {
        return self::select('categories.*', DB::raw('count(*) as number_of_entries'))
            ->leftJoin('merchandises', 'merchandises.category_id', '=', 'categories.id')
            ->groupBy('categories.id');
    }

    public static function sort($sort, $model = null) {
        if (!is_null($model)) {
            if ($sort == str_slug(trans('sort.categories')[0])) {
                return $model->orderBy('categories.name', 'asc');
            }

            if ($sort == str_slug(trans('sort.categories')[1])) {
                return $model->orderBy('categories.updated_at', 'desc');
            }

            if ($sort == str_slug(trans('sort.categories')[2])) {
                return $model->orderBy('number_of_entries', 'desc');
            } // todo not sorting properly

            if ($sort == str_slug(trans('sort.categories')[3])) {
                return $model->orderBy('number_of_entries', 'asc');
            } // todo not sorting properly
        }

        if ($sort == str_slug(trans('sort.categories')[0])) {
            return self::orderBy('name', 'asc');
        }

        if ($sort == str_slug(trans('sort.categories')[1])) {
            return self::orderBy('updated_at', 'desc');
        }

        if ($sort == str_slug(trans('sort.categories')[2])) {
            return self::withNumberOfEntries()->orderBy('number_of_entries', 'desc');
        } // todo not sorting properly

        if ($sort == str_slug(trans('sort.categories')[3])) {
            return self::withNumberOfEntries()->orderBy('number_of_entries', 'asc');
        } // todo not sorting properly
    }
}
