<?php

namespace App\Http\Controllers\Api\v1\SuperAdmin;

use App\Events\SendAnnouncementNotifications;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Announcement\AnnouncementResource;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $announcement = Announcement::paginate(9);
        return AnnouncementResource::collection($announcement);
    }

    public function send_unAnnouncedAnnouncements()
    {
        $unAnnouncedAnnouncements = $this->unAnnouncedAnnouncements();
        foreach ($unAnnouncedAnnouncements as $unAnnouncedAnnouncement){
            SendAnnouncementNotifications::dispatch($unAnnouncedAnnouncement->id);
        }
        return "done";
    }

    public static function unAnnouncedAnnouncements()
    {
        return Announcement::where('status' , 'pending')->where('send_at' , '<=' , now())->oldest()->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
//       $request->send_at= Carbon::createFromFormat('Y-m-d H:i:s', $request->send_at, 'UTC');
//        if (isset($request->send_at)) {
//            $request->send_at = date("Y-m-d H:i:s", $request->send_at);
//        }
        if (isset($request->send_at)) {
            $sendAt = Carbon::createFromTimestamp($request->send_at)->format('Y-m-d H:i:s');
            $request->merge(['send_at' => $sendAt]);
        }
//        //1684658820
//        return $request->send_at;
        $data = $request->validate([
            'workshop_id' => [Rule::excludeIf(!isset($request->workshop_id)), 'numeric', 'exists:App\Models\Workshop,id'],
            'users' => [Rule::excludeIf(!isset($request->users)), 'array'],
            "users.*" => [Rule::excludeIf(!isset($request->users)), 'numeric', 'exists:App\Models\User,id'],
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'kavenegar_data' => [Rule::excludeIf(!isset($request->kavenegar_data)), 'array', 'max:3'],
            'drivers' => [Rule::excludeIf(!isset($request->drivers)), 'array'],
            'send_at' => [Rule::excludeIf(!isset($request->send_at)), 'date', 'after:now'],
            // 'send_at' => [Rule::excludeIf(!isset($request->send_at))],
        ]);

        if (isset($data['users']))
            $data = array_merge($data, ['users' => json_encode($data['users'])]);
        if (isset($data['kavenegar_data']))
            $data = array_merge($data, ['kavenegar_data' => json_encode($data['kavenegar_data'])]);
        if (isset($data['drivers']))
            $data = array_merge($data, ['drivers' => json_encode($data['drivers'])]);
        $announcement = Announcement::create($data);
        return response([
            'data' => new AnnouncementResource($announcement),
            'message' => "بلاگ به صورت کامل ثبت شد",
            'status' => 'success'
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function show($id)
    {
        $announcement = Announcement::find($id);
        if (!$announcement) {
            return response([
                'message' => "یافت نشد",
                'status' => 'success'
            ], 400);
        }
        return new AnnouncementResource($announcement);

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
        if (isset($request->send_at)) {
            $sendAt = Carbon::createFromTimestamp($request->send_at)->format('Y-m-d H:i:s');
            $request->merge(['send_at' => $sendAt]);
        }
        $announcement = Announcement::find($id);
        if (!$announcement) {
            return response([
                'message' => "یافت نشد",
                'status' => 'failed'
            ], 400);
        }
        if ($announcement->status != 'pending') {
            return response([
                'message' => "این پیام ارسال شده است امکان تغییر وجود ندارد",
                'status' => 'failed'
            ], 400);
        }
        $data = $request->validate([
            'workshop_id' => [Rule::excludeIf(!isset($request->workshop_id)), 'numeric', 'exists:App\Models\Workshop,id'],
            'users' => [Rule::excludeIf(!isset($request->users)), 'array'],
            "users.*" => [Rule::excludeIf(!isset($request->users)), 'numeric', 'exists:App\Models\User,id'],
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'kavenegar_data' => [Rule::excludeIf(!isset($request->kavenegar_data)), 'array', 'max:3'],
            'drivers' => [Rule::excludeIf(!isset($request->drivers)), 'array'],
            'send_at' => [Rule::excludeIf(!isset($request->send_at)), 'date', 'after:now'],
        ]);
        try {
            if (isset($data['users']))
                $data = array_merge($data, ['users' => json_encode($data['users'])]);
            if (isset($data['kavenegar_data']))
                $data = array_merge($data, ['kavenegar_data' => json_encode($data['kavenegar_data'])]);
            if (isset($data['drivers']))
                $data = array_merge($data, ['drivers' => json_encode($data['drivers'])]);
            $announcement->update($data);
            return response([
                'data' => new AnnouncementResource($announcement),
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
        $announcement = Announcement::find($id);
        if (!$announcement) {
            return response([
                'message' => "یافت نشد",
                'status' => 'success'
            ], 400);
        }
        $announcement->delete();
        return response([
            'message' => 'عملیات با موفقیت انجام شد',
            'status' => 'success'
        ], 200);
    }
}
