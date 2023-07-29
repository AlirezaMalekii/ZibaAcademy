<?php

namespace App\Http\Controllers\Api\v1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Category\CategoryCollection;
use App\Http\Resources\V1\Category\CategoryResource;
use App\Models\Category;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Kavenegar\Exceptions\ApiException;
use Illuminate\Database\Query\Builder;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return CategoryCollection
     */
    public function index(Request $request)
    {
        if ($request->type=='blogs'){
            $categoryPiginate = Category::whereType('blog')->paginate(20);
            return new CategoryCollection($categoryPiginate);
        }
        if ($request->type=='workshops'){
            $categoryPiginate = Category::whereType('workshop')->paginate(20);
            return new CategoryCollection($categoryPiginate);
        }
        if ($request->type=='courses'){
            $categoryPiginate = Category::whereType('course')->paginate(20);
            return new CategoryCollection($categoryPiginate);
        }
        $categoryPiginate = Category::paginate(20);

        return new CategoryCollection($categoryPiginate);
    }

    /*public function all_category_blog()
    {
        $categoryPiginate = Category::whereType('blog')->paginate(2);
        return new CategoryCollection($categoryPiginate);
    }

    public function all_category_workshop()
    {
        $categoryPiginate = Category::whereType('workshop')->paginate(2);
        return new CategoryCollection($categoryPiginate);

    }*/

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'type' => 'required|string|max:150',
            'title' => 'required|string|max:150',
            'parent_id' => [
                Rule::excludeIf(!isset($request->parent_id)),
                'numeric',
                Rule::exists('categories','id')->where(function (Builder $query) use ($request) {
                    return $query->where('type', $request->type);
                }),
//                'exists:App\Models\Category,id'
            ],
        ]);

        try {
            if (Category::where('type', $request->type)->where('title', $request->title)->first()) {
                return response([
                    'message' => 'در این تایپ قبلا همچین کتگوری ثبت شده است',
                    'status' => 'failed'
                ], 400);
            }
            auth()->user()->categories()->create($fields);
            return response([
                'message' => 'اطلاعات با موفقیت ثبت شد. ',
                'status' => 'success'
            ], 200);
        } catch (ApiException $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 400);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $category = Category::whereId($id)->first();
        if (!$category) {
            return response([
                'message' => "یافت نشد",
                'status' => 'failed'
            ], 400);
        }
        return new CategoryResource($category);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $category = Category::whereId($id)->first();
        if (!$category) {
            return response([
                'message' => "یافت نشد",
                'status' => 'success'
            ], 400);
        }
        $fields = $request->validate([
            'title' => 'required|string|max:150',
            'parent_id' => [Rule::excludeIf(!isset($request->parent_id)),
                Rule::exists('categories','id')->where(function (Builder $query) use ($request) {
                    return $query->where('type', $request->type);
                }),
            ],
        ]);
        try {
            if ($repeatCategory = Category::where('type', $category->type)->where('title', $request->title)->first()) {
                if ($repeatCategory->title != $category->title)
                    return response([
                        'message' => 'در این تایپ قبلا همچین کتگوری ثبت شده است',
                        'status' => 'failed'
                    ], 400);
            }
            $category->update($fields);
            return response([
                'message' => 'اطلاعات با موفقیت ثبت شد. ',
                'status' => 'success'
            ], 200);
//        } catch (ApiException $e) {
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $category = Category::whereId($id)->first();
        if (!$category) {
            return response([
                'message' => "یافت نشد",
                'status' => 'failed'
            ], 400);
        }
        $category->delete();
        return response([
            'message' => 'عملیات با موفقیت انجام شد',
            'status' => 'success'
        ], 200);
    }
}
