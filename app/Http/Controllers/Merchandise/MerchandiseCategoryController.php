<?php

namespace App\Http\Controllers\Merchandise;

use App\Http\Controllers\Controller;
use App\Models\Merchandise;
use App\Models\MerchandiseCategory;
use App\Models\Setting;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Input;

class MerchandiseCategoryController extends Controller
{
    public function index() {
        $category_with_number_of_entries = MerchandiseCategory::withNumberOfEntries();

        if (request()->has('search')) {
            $results = Merchandise::searchFor(request()->get('search'), $category_with_number_of_entries);

            if (!$results->count()) {
                flash()->error(trans('search.empty', ['search' => request()->get('search')]));
            }

            if (request()->has('sort')) {
                $sorted_results = Merchandise::sort(request()->get('sort'), $results);

                if (is_null($sorted_results)) {
                    return redirect()->back();
                }
                $merchandise_categories = $sorted_results->paginate(intval(Setting::value('per_page')));
            } else {
                $merchandise_categories = $results->paginate(intval(Setting::value('per_page')));
            }
        } else {
            if (request()->has('sort')) {
                $sorted = MerchandiseCategory::sort(request()->get('sort'), $category_with_number_of_entries);

                if (is_null($sorted)) {
                    return redirect()->back();
                }
                $merchandise_categories = $sorted->paginate(intval(Setting::value('per_page')));
            } else {
                $merchandise_categories = $category_with_number_of_entries->paginate(intval(Setting::value('per_page')));
            }
        }
        $merchandise_categories->appends(request()->except('page'));
        return view('dashboard.admin.merchandise.category.index', compact('merchandise_categories'));
    }

    public function create() {
        return view('dashboard.admin.merchandise.category.create');
    }

    public function store(Requests\MerchandiseCategoryRequest $request) {
        $merchandise_category = MerchandiseCategory::create($request->only('name'));
        flash()->success(trans('category.created', ['name' => $merchandise_category->name]));

        if ($request->has('redirect')) {
            return redirect()->to($request->get('redirect'));
        }
        return redirect()->route('merchandise.categories.index');
    }

    public function show(MerchandiseCategory $merchandise_category) {
        $merchandise_by_category = Merchandise::byCategory($merchandise_category->id);

        if (request()->has('search')) {
            $results = Merchandise::searchFor(request()->get('search'), $merchandise_by_category);

            if (!$results->count()) {
                flash()->error(trans('search.empty', ['search' => request()->get('search')]));
            }

            if (request()->has('sort')) {
                $sorted_results = Merchandise::sort(request()->get('sort'), $results);

                if (is_null($sorted_results)) {
                    return redirect()->back();
                }
                $merchandises = $sorted_results->paginate(intval(Setting::value('per_page')));
            } else {
                $merchandises = $results->paginate(intval(Setting::value('per_page')));
            }
        } else {
            if (request()->has('sort')) {
                $sorted = Merchandise::sort(request()->get('sort'), $merchandise_by_category);

                if (is_null($sorted)) {
                    return redirect()->back();
                }
                $merchandises = $sorted->paginate(intval(Setting::value('per_page')));
            } else {
                $merchandises = $merchandise_by_category->paginate(intval(Setting::value('per_page')));
            }
        }
        $merchandises->appends(request()->except('page'));
        return view('dashboard.admin.merchandise.category.show', compact('merchandises', 'merchandise_category'));
    }

    public function edit(MerchandiseCategory $merchandise_category) {
        return view('dashboard.admin.merchandise.category.edit', compact('merchandise_category'));
    }

    public function update(Requests\MerchandiseCategoryRequest $request, MerchandiseCategory $merchandise_category) {
        $merchandise_category->update($request->only('name'));
        flash()->success(trans('category.updated', ['name' => $merchandise_category->name]));

        if ($request->has('redirect')) {
            return redirect()->to($request->get('redirect'));
        }
        return redirect()->route('merchandise.categories.index');
    }

    public function destroy(MerchandiseCategory $merchandise_category) {
        if (count($merchandise_category->merchandises)) {
            flash()->error(trans('category.not_empty', ['name' => $merchandise_category->name]))->important();
        } else {
            $merchandise_category->delete();
            flash()->success(trans('category.deleted', ['name' => $merchandise_category->name]));
        }

        if (request()->has('redirect')) {
            return redirect()->to(request()->get('redirect'));
        }
        return redirect()->back();
    }
}
