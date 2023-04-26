<?php

namespace App\Http\Controllers\Api\v1\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;
use App\Http\Controllers\AdminController;
use Illuminate\Validation\Rules;
use Kavenegar\Exceptions\ApiException;
use function Composer\Autoload\includeFile;

class UserController extends AdminController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //created_by //admin_token
        $fields = $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone' => 'required|digits:11|unique:users',
            'level' => ['string', 'max:30', Rule::excludeIf(!isset($request->level))]
        ]);
        $user = auth()->user()->parent_user()->create($fields);
        if ($user->level === 'admin') {
            $token = self::code(User::class, 'admin_token', 70);
            $user->update(['admin_token' => $token]);
        }
        $this->send_sms_lookup([
            'receptor' => $user->phone,
            'template' => "welcome",
            'token' => URL::route('lwo')
        ]);
        return response([
            'message' => "کاربر جدید با موفقیت ثبت شد",
            'status' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        $user = User::whereId($id)->first();
        if (!$user) {
            return response([
                'message' => "کاربر یافت نشد",
                'status' => 'success'
            ], 400);
        }
        $fields = $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'phone' => ['required', 'digits:11', Rule::unique('users')->ignore($user->id)],
            'email' => [Rule::excludeIf(!isset($request->email)), 'string', 'Email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'level' => [Rule::excludeIf(!isset($request->level)), 'string', 'max:30'],
            'password' => [Rule::excludeIf(!isset($request->password)),'string','min:8','max:20',Rules\Password::defaults()],
            'active'=>'required|boolean'
        ]);

        if (key_exists('password',$fields)){
            $fields=array_merge($fields,['password' => bcrypt($fields['password'])]);
        }
        try {
            $user->update($fields);
            return response([
                'message' => 'اطلاعات با موفقیت ثبت شد. ',
                'status' => 'success'
            ], 200);
        }catch (ApiException $e){
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
        //
    }
}
