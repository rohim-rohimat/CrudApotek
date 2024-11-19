<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserExport;

class UserController extends Controller
{

    public function exportExcel()
    {
        return Excel::download(new UserExport, 'rekap-pembelian.xlsx');
    }

    public function index()
    {
        $user = user::all();
        return view('user.index', compact('user'));
    }

    public function create()
    {
        return view('user.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => "required",
            'email' => "required",
            'role' => "required",
            'password' => "required",
        ],[
            'name.required' => 'nama harus diisi',
            'email.required' => 'email harus diisi',
            'password.required' => 'password harus diisi',
        ]);
       User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role
        ]);
        return redirect()->back()->with('success', 'Berhasil menambahkan data user');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::find('id', $id)->first();
        return view ('user.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
           'name' => 'required|min:3',
           'email' => 'required',
           'role' => 'required',
        ]);

        User::where('id', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make( Str::random(8)),
        ]);

        return redirect()->route('user.home')->with('seccess', 'Berhasil mengubah data!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $deleteAkun = User::where('id', $id)->delete();

        if ($deleteAkun) {
            return redirect ()->back()->with('success', 'Berhasil menghapus akun');
        } else {
            return redirect()->back()->with('eror', 'Gagal menghapus akun');
        }
    }

    public function showLogin()
    {
        return view('user.login');
    }
    public function loginAuth(request $request)
    {
        $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required',
        ]);

        $user = $request->only(['email', 'password']);
        if (Auth::attempt($user)) {
            return redirect()->route('landing-page');
        }else {
            return redirect()->back()->with('failed', 'Proses login gagal,silahkan coba kembali dengan data yang benar!');
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login.auth')->with('Succes', 'Anda telah logout');
    }

    public function downloadPDF($id)
    {
        $user = user::where('id', $id)->first()->toArray();
        view()->share('user',$user);
        $pdf =pdf::loadView('order.kasir.pdf', $user);
        return $pdf->download('struk-pembelian.pdf');
    }
}
