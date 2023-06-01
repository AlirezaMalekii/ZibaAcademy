<?php

namespace App\Http\Controllers\Api\v1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Setting\SettingResource;
use App\Http\Resources\V1\User\UserResource;
use App\Models\Setting;
use Illuminate\Filesystem\Filesystem as FileSystem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class SettingController extends Controller
{
    public function info()
    {
        return new UserResource(auth()->user(), true);
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $fields = $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone' => ['required', 'digits:11', Rule::unique('users')->ignore($user->id)],
            'email' => [Rule::excludeIf(!isset($request->email)), 'string', 'Email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => [Rule::excludeIf(!isset($request->password)), 'string', 'min:8', 'max:20', Rules\Password::defaults()],
        ]);

        if (key_exists('password', $fields)) {
            $fields = array_merge($fields, ['password' => bcrypt($fields['password'])]);
        }
        try {
            $user->update($fields);
            return response([
                'message' => 'اطلاعات با موفقیت ثبت شد. ',
                'status' => 'success'
            ], 200);
        } catch (\Exception $e) {
            return response([
                'message' => $e->getMessage(),
                'status' => 'failed'
            ], 400);
        }
    }

    public function update_home(Request $request)
    {
        $data = $request->validate([
            'title_home' => 'string|required|max:60',
            'body_home' => 'string|required|max:200',
            'video_type' => ['required', 'max:25', 'string', Rule::in(['aparat', 'video'])],
            'video' => 'array|required',
        ]);
        if ($request->video_type == 'video') {
            if ($data['video']['filePath'] == null) {
                return response([
                    'message' => 'ویدیو انتخاب نشده است.',
                    'status' => 'failed'
                ], 400);
            }
        }
        if ($request->video_type == 'aparat') {
            if ($data['video'] == null) {
                return response([
                    'message' => 'ویدیو انتخاب نشده است.',
                    'status' => 'failed'
                ], 400);
            }
        }
        $setting = Setting::find(1);
        $setting->update([
            'title_home' => $data['title_home'],
            'body_home' => $data['body_home'],
        ]);
        if ($data['video_type'] == 'aparat') {
            $video = $setting->files()->where(function ($q) {
                $q->where('type', 'aparat')->orWhere('type', 'video');
            })->first();
            if ($video->type == 'video') {
                if (file_exists(public_path($video->file['path']))) {
                    $filedeleted = unlink(public_path($video->file['path']));
                }
            }
            $video->delete();

            $setting->files()->create([
//                'creator_id' => 1,
                    'creator_id' => auth()->user()->id,
                'file' => $data['video'],
                'type' => $data['video_type'],
//                    'file_name' => $array_cover_image[4],
//                    'extension' => $extension_cover[1],
                'accessibility' => 'free'
            ]);
        }
        if ($data['video_type'] == 'video') {
            if ($video = $setting->files->where('type', 'video')->first()) {
                if ($video->file['path'] != $data['video']['filePath']['path']) {
                    if (file_exists(public_path($video->file['path']))) {
                        $filedeleted = unlink(public_path($video->file['path']));
                    }
                    $video->delete();
                    $setting->files()->create([
//                        'creator_id' => 1,
                    'creator_id' => auth()->user()->id,
                        'file' => $data['video']['filePath'],
                        'type' => $data['video_type'],
                        'extension' => $data['video']['fileExtension'],
                        'accessibility' => 'free'
                    ]);
                }
            } else {
                $video = $setting->files->where('type', 'aparat')->first();
                $video->delete();
                $setting->files()->create([
//                    'creator_id' => 1,
                    'creator_id' => auth()->user()->id,
                    'file' => $data['video']['filePath'],
                    'type' => $data['video_type'],
                    'extension' => $data['video']['fileExtension'],
                    'accessibility' => 'free'
                ]);
            }
        }
        return response([
            'message' => "تنظیمات به صورت کامل ثبت شد",
            'status' => 'success'
        ], 200);
    }

    public function setting_info()
    {
        $setting = Setting::find(1);
        return response([
            'data' => new SettingResource($setting),
            'status' => 'success'
        ], 200);
    }
}
