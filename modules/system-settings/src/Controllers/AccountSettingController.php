<?php

namespace SkylarkSoft\GoRMG\SystemSettings\Controllers;

use Image;
use Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use SkylarkSoft\GoRMG\SystemSettings\Models\User;
use SkylarkSoft\GoRMG\BasicFinance\Models\Department;

class AccountSettingController extends Controller
{
    public function accountSettings()
    {
        $acc_departments = Department::pluck('department', 'id')->prepend('Select Department','');
        return view('system-settings::forms.account_settings', ['users_info' => Auth::user(), 'acc_departments' => $acc_departments]);
    }

    public function UpdateAccountSettings(Request $request, $id)
    {
        $request->validate([
            'first_name' => 'required|max:50',
            'email' => 'required|max:55|unique:users,email,'.$id,
            'profile_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'signature' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        try {
            $user = User::findOrFail($id);
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->phone_no = $request->phone_no;
            $user->address = $request->address;
            $user->designation = $request->designation;
            $user->acc_department_id = $request->acc_department_id;
            $user->screen_name = $request->first_name .' '.$request->last_name ?? '';
            if ($request->hasFile('profile_image')) {
                $user_data = User::where('id', $id)->first();
                if (isset($user_data->profile_image)) {
                    $file_name_to_delete = $user_data->profile_image;
                    if ( $this->hasPrevImage($file_name_to_delete) ) {
                        Storage::delete('profile_image/' . $file_name_to_delete);
                    }
                }
                $time = time();
                $file = $request->profile_image;
                $file->storeAs('profile_image', $time . $file->getClientOriginalName());
                $user->profile_image = $time . $file->getClientOriginalName();
            }
            if ($request->hasFile('signature')) {
                $imageTmp = $request->file('signature');
                if($imageTmp->isValid()) {
//                    $extension = $imageTmp->getClientOriginalExtension();
//                    $imageName = time().'.'.$extension;
                    $destination = 'user';
                    $imagePath = Storage::put($destination, $imageTmp);
//                    Image::make($imageTmp)
//                        ->resize(300, 80)
//                        ->encode('png', 75)
//                        ->save($imagePath);

                    $user->signature = $imagePath;
                }
            }
            $user->save();
            Session::flash('success', 'Updated successfully!!');

            return redirect()->back();
        } catch (\Exception $e) {
            Session::flash('error', 'Something worng!!. Please try again');

            return redirect()->back();
        }
    }

    public function changePasswordForm()
    {
        return view('system-settings::forms.change_password');
    }

    public function changePasswordPost(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|same:new_password',
        ]);

        // check current password valid or invalid
        if (! (Hash::check($request->get('current_password'), Auth::user()->password))) {
            Session::flash('error', 'Your current password does not match.');

            return redirect()->back();
        }

        $user = Auth::user();
        $user->password = bcrypt($request->new_password);
        if ($user->save()) {
            Session::flash('success', 'Password changed successfully!!');
        } else {
            Session::flash('error', 'Something worng!!. Please try again');
        }

        return redirect()->back();
    }

    /**
     * @param $file_name_to_delete
     * @return bool
     */
    public function hasPrevImage($file_name_to_delete): bool
    {
        return Storage::disk('public')->exists('/profile_image/' . $file_name_to_delete) && $file_name_to_delete != null;
    }
}
