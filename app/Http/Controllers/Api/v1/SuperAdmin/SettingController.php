<?php

namespace App\Http\Controllers\Api\v1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\User\UserResource;
use Illuminate\Http\Request;
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
}
