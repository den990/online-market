<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\File;

class ProfileController extends Controller
{
    public function index(User $user)
    {
        return view('profile.index', compact('user'));
    }

    public function edit()
    {
        return view('profile.edit');
    }

    public function update(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        $validator = $request->validated();


        if ($request->hasFile('avatar_id')) {
            $file = $request->file('avatar_id');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('uploads', $fileName, 'public');

            if ($user->avatar_id) {
                $fileModel = File::find($user->avatar_id);

                if ($fileModel && Storage::disk('public')->exists($fileModel->file_name)) {
                    Storage::disk('public')->delete($fileModel->file_name);
                }

                $fileModel->file_name = $filePath;
                $fileModel->updated_at = now();
                $fileModel->save();
            } else {
                $fileModel = new File;
                $fileModel->file_name = $filePath;
                $fileModel->save();
                $user->avatar_id = $fileModel->id;
            }
        }

        $user->name = $request->input('name');
        $user->phone = $request->input('phone');
        $user->city_id = $request->input('city_id');
        $user->email = $request->input('email');

        $user->save();

        return redirect()->route('profile.index')->with('status', 'Profile updated successfully.');
    }


    public function __construct()
    {
        $this->middleware('auth');
    }
}
