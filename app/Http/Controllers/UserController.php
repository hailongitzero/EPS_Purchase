<?php

namespace App\Http\Controllers;

use App\Http\Requests\InsertProfile;
use App\Http\Requests\UpdateProfile;
use App\Main\Users;
use App\Main\Utils;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class UserController extends Controller
{
     /**
     * Get all moderator
     * @param Illuminate\Http\Request
     * @return \Illuminate\Http\Responsea
     */
    public function getModerator()
    {
        $mod = Users::getModerators();

        return response($mod->toJson(), 200)->header('Content-Type', 'application/json');
    }

    public function add(InsertProfile $request)
    {
        try{
            $user = new User();
            $user->name = $request->input('name');
            $user->department_id = $request->input('department');
            $user->username = $request->input('username');
            $user->position = $request->input('position');
            $user->email = $request->input('email');
            $user->role = $request->input('role');
            $user->gender = $request->input('gender');
            $user->telephone = $request->input('telephone');

            $user->save();

            return response(['message' => 'Thêm người dùng Thành công.'], 200)->header('Content-Type', 'application/json');
        } catch (Exception $e) {
            return response(['message' => 'Thêm người dùng thất bại.'], 500)->header('Content-Type', 'application/json');
        }
    }

    public function update(UpdateProfile $request)
    {
        $curUser = Auth::user();
        $curRole = $curUser->role;
        if ( $curRole != Utils::NHAN_VIEN ){
            $email = $request->input('email');
        } else {
            $email = $curUser->email;
        }
        $user = User::where('email', $email)->first();

        if ( $curRole != Utils::NHAN_VIEN ){
            $user->role = $request->input('role');
        }
        $user->department_id = $request->input('department');
        $user->position = $request->input('position');
        $user->telephone = $request->input('telephone');
        $user->description = $request->input('description');
        $user->gender = $request->input('gender');
        
        if ($request->hasFile('avatar')) {
            $user->photo = $this->uploadAvatar($request->file('avatar'));
        }
        
        $user->save();

        return redirect()->back();
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);
        try{
            $role = Auth::user()->role;
            if ( $role == Utils::NHAN_VIEN ) {
                $user = User::where('email', Auth::user()->email)->first();
            }else {
                $user = User::find($request->input('id'));
            }
            $user->password = Hash::make($request->input('password'));
            $user->remember_token = Str::random(60);
            $user->save();
    
            event(new PasswordReset($user));
            
            return  redirect()->back()->with('status', 'Đổi mật khẩu thành công');
        } catch (Exception $e){
            return redirect()->back()->withErrors(['email' => 'Đổi mật khẩu không thành công.']);
        }
    }

    public function delete(Request $request)
    {
        try{
            $username = $request->input('username');
            User::where('username', $username)->delete();
            return response(['message' => 'Xoá người dùng thành công.'], 200)->header('Content-Type', 'application/json');
        } catch (Exception $e){
            return response(['message' => 'Xoá người dùng thất bại. Vui lòng thử lại.'], 500)->header('Content-Type', 'application/json');
        }
    }

    /**
     * upload avatar
     */
    public function uploadAvatar($file){
        $allowedfileExtension=['jpg','png'];
        $path = '';
        $filename = strtotime(date("D M d, Y G:i")) . '_' . $file->getClientOriginalName();

        $fileExtension = strtolower($file->getClientOriginalExtension());
        $checkExtension = in_array($fileExtension, $allowedfileExtension);

        if ($checkExtension){
            $path = $file->storeAs('public/users', $filename);
        }
        return Storage::url('users/'.$filename);
    }
}
