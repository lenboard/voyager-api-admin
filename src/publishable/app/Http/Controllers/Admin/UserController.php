<?php

namespace App\Http\Controllers\Admin;

use TCG\Voyager\Http\Controllers\VoyagerBaseController;
use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Events\BreadDataUpdated;
use App\Models\Post\Post;
use TCG\Voyager\Events\BreadDataAdded;

class UsertController extends VoyagerBaseController
{
    /**
     * POST BRE(A)D - Store data.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Check permission
        $this->authorize('add', app($dataType->model_name));

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->addRows);

        if ($val->fails()) {
            return response()->json(['errors' => $val->messages()]);
        }

        if (!$request->has('_validate')) {
            $data = $this->insertUpdateData($request, $slug, $dataType->addRows, new $dataType->model_name());

            event(new BreadDataAdded($dataType, $data));

            if ($request->ajax()) {
                return response()->json(['success' => true, 'data' => $data]);
            }

            if ($request->featured === 'on' && $request->attach === 'on') {
                Post::where('id', '<>', $data->id)->update(['featured' => 0]);
            }

            return redirect()
                ->route("voyager.{$dataType->slug}.index")
                ->with([
                        'message'    => __('voyager::generic.successfully_added_new')." {$dataType->display_name_singular}",
                        'alert-type' => 'success',
                    ]);
        }
    }

    // POST BR(E)AD
    public function update(Request $request, $id)
    {
        $slug = $this->getSlug($request);

        $dataType = Voyager::model('DataType')->where('slug', '=', $slug)->first();

        // Compatibility with Model binding.
        $id = $id instanceof Model ? $id->{$id->getKeyName()} : $id;

        $data = call_user_func([$dataType->model_name, 'findOrFail'], $id);

        // Check permission
        $this->authorize('edit', $data);

        // Validate fields with ajax
        $val = $this->validateBread($request->all(), $dataType->editRows, $dataType->name, $id);

        if ($val->fails()) {
            return response()->json(['errors' => $val->messages()]);
        }

        if (!$request->ajax()) {
            $this->insertUpdateData($request, $slug, $dataType->editRows, $data);

            event(new BreadDataUpdated($dataType, $data));

            if ($request->featured === 'on' && $request->attach === 'on') {
                Post::where('id', '<>', $id)->update(['featured' => 0]);
            }

            return redirect()
                ->route("voyager.{$dataType->slug}.index")
                ->with([
                    'message'    => __('voyager::generic.successfully_updated')." {$dataType->display_name_singular}",
                    'alert-type' => 'success',
                ]);
        }
    }
}
