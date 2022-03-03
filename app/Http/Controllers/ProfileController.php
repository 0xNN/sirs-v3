<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileRequest;
use App\Http\Requests\PasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Show the form for editing the profile.
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        return view('profile.edit');
    }

    /**
     * Update the profile
     *
     * @param  \App\Http\Requests\ProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileRequest $request)
    {
        auth()->user()->update($request->all());

        return back()->withStatus(__('Profile successfully updated.'));
    }

    /**
     * Change the password
     *
     * @param  \App\Http\Requests\PasswordRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function password(PasswordRequest $request)
    {
        auth()->user()->update(['password' => Hash::make($request->get('password'))]);

        return back()->withPasswordStatus(__('Password successfully updated.'));
    }

    public function dt_user(Request $request)
    {
        if($request->ajax()) {
            $users = User::all();
            return datatables()
            ->of($users)
            ->addIndexColumn()
            ->editColumn('action', function($row) {
                $button = '<div class="btn-group btn-group-sm" role="group">';
                // $button .= '<button id="btn-sinkron" data-id="'.$row->id.'" class="btn-sinkron btn btn-sm btn-info"><i class="fas fa-share"></i></button>';
                // $button .= '<button id="btn-delete-sinkron" data-id="'.$row->id.'" class="btn-delete-sinkron btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>';
                $button .= '</div>';

                return $button;
            })
            ->editColumn('role_id', function($row) {
                if($row->role_id == 1) {
                    return 'Admin';
                }
                return 'User Lapangan';
            })
            ->editColumn('akses_id', function($row) {
                if($row->akses_id == 1) {
                    return 'User Covid';
                }
                return 'User Non Covid';
            })
            ->escapeColumns([])
            ->make(true);
        }
    }

    public function create()
    {
        return view('users.create');
    }

    public function simpanuser(Request $request)
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'akses_id' => $request->akses_id
        ];
        $user = User::updateOrCreate([
            'email' => $request->email,
        ],$data);
        if($user) {
            return JsonResponseController::jsonDataWithIcon($user,'Berhasil Disimpan!','success');
        }
        return JsonResponseController::jsonDataWithIcon($user,'Gagal Disimpan!','error');
    }
}

