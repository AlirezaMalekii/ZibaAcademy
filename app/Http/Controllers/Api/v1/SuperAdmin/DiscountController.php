<?php

namespace App\Http\Controllers\Api\v1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Discount\DiscountCollection;
use App\Http\Resources\V1\Discount\DiscountResource;
use App\Http\Resources\V1\Discount\DiscountUserResource;
use App\Models\Discount;
use App\Models\DiscountUser;
use App\Models\Workshop;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;
use function PHPUnit\Framework\isEmpty;

class DiscountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $discounts = Discount::filter()->latest()->paginate(20);
        return new DiscountCollection($discounts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (isset($request->expire_date)) {
            $sendAt = Carbon::createFromTimestamp($request->expire_date)->format('Y-m-d H:i:s');
            $request->merge(['expire_date' => $sendAt]);
        }
//        return  new DiscountResource(Discount::find(10),true);
        $data = $request->validate([
            'code' => 'required|unique:discounts|max:25|string',
            'type' => ['required', 'max:25', 'string', Rule::in(['public', 'private'])],
            'percent' => ['prohibited_unless:amount,null', 'required_without:amount', Rule::excludeIf(!isset($request->percent)), 'integer', 'min:1', 'max:100'],
            'amount' => ['prohibited_unless:percent,null', 'required_without:percent', Rule::excludeIf(!isset($request->amount)), 'digits_between:3,7', 'numeric'],
            'use_limit' => [Rule::excludeIf(!isset($request->use_limit)), 'integer'],
            'expire_date' => [Rule::excludeIf(!isset($request->expire_date)), 'date', 'after:now'],
            'discount_item' => ['required', 'array'],
            'discount_item.*' => ['exists:workshops,id'],
            'users_id' => [Rule::excludeIf(!isset($request->users_id)), 'array'],
            'users_id.*' => [Rule::excludeIf(!isset($request->users_id)), 'exists:users,id'],
        ]);
        if ($request->type == 'public') {
            if (!empty($request->users_id)) {
                return response([
                    'massage' => 'فقط در حالت خصوصی امکان اضافه کردن کاربر وجود دارد',
                    'status' => 'failed'
                ], 400);
            }
            $filtered = array_merge(Arr::except($data, ['discount_item', 'users_id']), [
                'creator_id' => auth()->user()->id,
            ]);
            $discount = Discount::create($filtered);
            $workshops = Workshop::whereIn('id', $data['discount_item'])->get();
            foreach ($workshops as $workshop) {
                $workshop->discount_items()->create([
                    'discount_id' => $discount->id
                ]);
            }
            return response([
                'data' => new DiscountResource($discount, true),
                'massage' => 'کد تخفیف با موفقیت ساخته شد.',
                'status' => 'success'
            ], 200);
        }
        if ($request->type == 'private') {
            if (empty($request->users_id)) {
                return response([
                    'massage' => 'لطفا کاربران شامل تخغیف را اضافه کنید',
                    'status' => 'failed'
                ], 400);
            }
            $filtered = array_merge(Arr::except($data, ['discount_item', 'users_id']), [
                'creator_id' => auth()->user()->id,
            ]);
            $discount = Discount::create($filtered);
            $workshops = Workshop::whereIn('id', $data['discount_item'])->get();
            foreach ($workshops as $workshop) {
                $workshop->discount_items()->create([
                    'discount_id' => $discount->id
                ]);
            }
            $discount->users()->sync($data['users_id']);
            return response([
                'data' => new DiscountResource($discount, true),
                'massage' => 'کد تخفیف با موفقیت ساخته شد.'
            ], 200);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $discount = Discount::find($id);
        if (!$discount) {
            return response([
                'message' => "یافت نشد",
                'status' => 'failed'
            ], 400);
        }
//        /*return */       dd($discount->users()->get()->first()->pivot->discount_id) ;
        return new DiscountResource($discount);
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
        $discount = Discount::find($id);
        if (!$discount) {
            return response([
                'message' => "یافت نشد",
                'status' => 'failed'
            ], 400);
        }
        if (isset($request->expire_date)) {
            $sendAt = Carbon::createFromTimestamp($request->expire_date)->format('Y-m-d H:i:s');
            $request->merge(['expire_date' => $sendAt]);
        }
        $data = $request->validate([
            'code' => ['required', 'max:25', 'string', Rule::unique('discounts')->ignore($discount->id)],
            'type' => ['required', 'max:25', 'string', Rule::in(['public', 'private'])],
            'percent' => ['prohibited_unless:amount,null', 'required_without:amount', Rule::excludeIf(!isset($request->percent)), 'integer', 'min:1', 'max:100'],
            'amount' => ['prohibited_unless:percent,null', 'required_without:percent', Rule::excludeIf(!isset($request->amount)), 'digits_between:3,7', 'numeric'],
            'use_limit' => [Rule::excludeIf(!isset($request->use_limit)), 'integer'],
            'expire_date' => [Rule::excludeIf(!isset($request->expire_date)), 'date', 'after:now'],
            'discount_item' => ['required', 'array'],
            'discount_item.*' => ['exists:workshops,id'],
            'users_id' => [Rule::excludeIf(!isset($request->users_id)), 'array'],
            'users_id.*' => [Rule::excludeIf(!isset($request->users_id)), 'exists:users,id'],
            'active' => 'required|boolean'
        ]);
        if ($request->type == 'public') {
            /*if (!empty($request->users_id)) {
                return response([
                    'massage' => 'فقط در حالت خصوصی امکان اضافه کردن کاربر وجود دارد',
                    'status' => 'failed'
                ], 400);
            }
            $filtered = array_merge(Arr::except($data, ['discount_item', 'users_id']), [
                'creator_id' => auth()->user()->id,
            ]);*/
//            $workshop_id_exist= array_values($discount->discount_items->pluck('discount_id')->toArray());
//            $workshop_id_exist= $discount->discount_items->pluck('discountable_id')->toArray();
//            $deletes = array_diff($workshop_id_exist,$workshop_id);
//            $adds = array_diff($workshop_id,$workshop_id_exist);
//            foreach ($deletes as $delete){
//                $posts->photos($photoModel)->detach();
//            }
            /* $workshop_id_exist= $discount->discount_items()->delete();
             $workshop_id_exist= $discount->discount_items()->create();*/
//            $workshop_id= $data['discount_item'];
            /*return $workshop_id_exist;
            $discount->update($filtered);
            $workshops = Workshop::whereIn('id', $data['discount_item'])->get();
            foreach ($workshops as $workshop) {
                $workshop->discount_items()->create([
                    'discount_id' => $discount->id
                ]);
            }
            return response([
                'data' => new DiscountResource($discount, true),
                'massage' => 'کد تخفیف با موفقیت ساخته شد.',
                'status' => 'success'
            ], 200);*/
            if (!empty($request->users_id)) {
                return response([
                    'massage' => 'فقط در حالت خصوصی امکان اضافه کردن کاربر وجود دارد',
                    'status' => 'failed'
                ], 400);
            }
            $filtered = Arr::except($data, ['discount_item', 'users_id']);
            $discount->update($filtered);
            $discount->discount_items()->delete();
            $workshops = Workshop::whereIn('id', $data['discount_item'])->get();
            foreach ($workshops as $workshop) {
                $workshop->discount_items()->create([
                    'discount_id' => $discount->id
                ]);
            }
            return response([
                'data' => new DiscountResource($discount, true),
                'massage' => 'کد تخفیف با موفقیت ساخته شد.',
                'status' => 'success'
            ], 200);
        }
        if ($request->type == 'private') {
            if (empty($request->users_id)) {
                return response([
                    'massage' => 'لطفا کاربران شامل تخغیف را اضافه کنید',
                    'status' => 'failed'
                ], 400);
            }
            $filtered = Arr::except($data, ['discount_item', 'users_id']);
            $discount->update($filtered);
            $discount->discount_items()->delete();
            $workshops = Workshop::whereIn('id', $data['discount_item'])->get();
            foreach ($workshops as $workshop) {
                $workshop->discount_items()->create([
                    'discount_id' => $discount->id
                ]);
            }
            $discount->users()->sync($data['users_id']);
            return response([
                'data' => new DiscountResource($discount, true),
                'massage' => 'کد تخفیف با موفقیت ساخته شد.'
            ], 200);
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
        $discount = Discount::find($id);
        if (!$discount) {
            return response([
                'message' => "یافت نشد",
                'status' => 'failed'
            ], 400);
        }
        $discount->discount_items()->delete();
        if ($discount->type == 'private') {
            $discount->users()->detach();
        }
        $discount->delete();
        return response([
            'message' => 'عملیات با موفقیت انجام شد',
            'status' => 'success'
        ], 200);
    }

    public function discount_user()
    {
//        dd(121);
        $discount_users=DiscountUser::whereNotNull('used_at')->latest()->paginate(50);
//        dd($discount_users);
        return  DiscountUserResource::collection($discount_users);
    }
}
