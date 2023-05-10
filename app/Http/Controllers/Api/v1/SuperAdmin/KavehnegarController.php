<?php

namespace App\Http\Controllers\Api\v1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Kavenegar\KavenegarTemplateResource;
use App\Models\KavenegarTemplate;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KavehnegarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $template_pagination=KavenegarTemplate::paginate(10);
       return KavenegarTemplateResource::collection($template_pagination);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:100',
            'name' => 'required|string|max:100|unique:kavenegar_templates',
            'message' => 'required|string'
        ]);
        try {
            $kavenegar=KavenegarTemplate::create($data);
            return response([
                'data' => new KavenegarTemplateResource($kavenegar),
                'message' => "الگوی کاوه نگار به صورت کامل ثبت شد",
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => 'بخشی از عملیات با خطا مواجه شد.'
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return KavenegarTemplateResource
     */
    public function show($id)
    {
        $kavenegar_template = KavenegarTemplate::whereId($id)->first();
        if (!$kavenegar_template) {
            return response([
                'message' => "یافت نشد",
                'status' => 'failed'
            ], 400);
        }
       return new KavenegarTemplateResource($kavenegar_template);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
//        return "fgf";
        $kavenegar_template = KavenegarTemplate::whereId($id)->first();
        if (!$kavenegar_template) {
            return response([
                'message' => "یافت نشد",
                'status' => 'failed'
            ], 400);
        }
        $data = $request->validate([
            'title' => 'required|string|max:100',
            'name' => ['required','string','max:100',Rule::unique('kavenegar_templates')->ignore($kavenegar_template->id)],
            'message' => 'required|string',
            'status'=>'required|string'
        ]);
        try {
            if (!in_array($request->status,['pending','approved','disapproved'])){
                return response([
                    'message' => "وضعیت ارسال شده جز الگوی تعریفی نمی باشد.",
                    'status' => 'failed'
                ], 400);
            }
            $kavenegar_template->update($data);
            return response([
                'data' => new KavenegarTemplateResource($kavenegar_template),
                'message' => "تغییرات به درستی ثبت شد",
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => 'بخشی از عملیات با خطا مواجه شد.'
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
        $kavenegar_template = KavenegarTemplate::whereId($id)->first();
        if (!$kavenegar_template) {
            return response([
                'message' => "یافت نشد",
                'status' => 'failed'
            ], 400);
        }
        $kavenegar_template->delete();
        return response([
            'message' => 'عملیات با موفقیت انجام شد',
            'status' => 'success'
        ], 200);
    }
}
