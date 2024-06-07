<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    public function index()
    {
        return view('admin.profile.index');
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|string|lowercase|email|max:255|unique:users,email,' . Auth::user()->id,
            'image' => 'nullable|image|mimes:jpg,jpeg,png,svg,webp|max:5120'
        ]);

        $user = Auth::user();

        if ($request->hasFile('image')) {
            // Delete existing image
            File::delete(public_path($user->image));

            $image = $request->image;
            $imageName = uniqid() . '_' . $image->getClientOriginalName();
            $image->move(public_path('/uploads'), $imageName);

            $user->image = "/uploads/$imageName";
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return back();
    }

    /** Update password */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8']
        ]);

        $request->user()->update([
            'password' => bcrypt($request->password)
        ]);

        return back();
    }
}
