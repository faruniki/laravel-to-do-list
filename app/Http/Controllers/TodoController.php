<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TodoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function login()
    {
        return view('dashboard.login');
    }

    public function register()
    {
        return view('dashboard.register');
    }

    public function inputRegister(Request $request)
    {
        // testing hasil input
        // dd($request->all());
        // validasi atau aturan value column pada db
        $request->validate([
            'email' => 'required',
            'name' => 'required|min:4|max:50',
            'username' => 'required|min:4|max:8',
            'password' => 'required',
        ]);
        // tambah data ke db bagian table users
        User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        // apabila berhasil, bkl diarahin ke hlmn login dengan pesan success
        return redirect('/')->with('success', 'berhasil membuat akun!');
    }

    public function auth(Request $request)
    {
        $request->validate([
            'username' => 'required|exists:users,username',
            'password' => 'required',
        ],[
            'username.exists' => "This username doesn't exists"
        ]);

        $user = $request->only('username', 'password');
        if (Auth::attempt($user)) {
            return redirect()->route('todo.index');
        } else {
            // dd('salah');
            return redirect('/')->with('fail', "Gagal login, periksa dan coba lagi!");
        }
    }

    public function logout()
    {
        // menghapus history login
        Auth::logout();
        // mengarahkan ke halaman login lagi
        return redirect('/');
    }

    public function index()
    {
        //menampilkan halaman awal, semua data
        // cari data todo yang punya user_id nya sama dengan id orang yang login. kalo ketemu datanya diambil
        $todos = Todo::where([
            ['user_id', '=', Auth::user()->id],
            ['status', '=', 0],
        ])->get();
        //tampilin file index di folder dashboard dan bawa data dari variable yang namanya todos ke fil tersebut
        return view('dashboard.index', compact('todos'));
    }

    public function complated()
    {
        $todos = Todo::where([
            ['user_id', '=', Auth::user()->id],
            ['status', '=', 1],
        ])->get();
        return view('dashboard.complated', compact('todos'));
    }

    public function updateComplated($id)
    {
        // $id pada parameter mengambil data dari path dinamis {id}
        // cari data yang memiliki value column id sama dengan data id yang dikirim  ke route, maka update baris data tersebut
        Todo::where('id', $id)->update([
            'status' => 1,
            'done_time' => Carbon::now(),
        ]);
        // kalao berhasil bakal diarahin ke halaman list todo yang completed dengan pemberitahuan
        return redirect()->route('todo.complated')->with('done', 'Todo sudah selesai dikerjakan!');
    }

    public function create()
    {
        //menampilkan halaman input form tambah data
        return view('dashboard.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //mengirim data ke database (data baru) / menambahkan data baru ke db
            //validasi
            $request->validate([
                'title' => 'required|min:3',
                'date' => 'required',
                'description' => 'required|min:8',
            ]);
            //tambah data ke database
            Todo::create([
                'title' => $request->title,
                'description' => $request->description,
                'date' => $request->date,
                'status' => 0,
                'user_id' => Auth::user()->id,
            ]);
            //redirect apabila berhasil bersama dengan alert-nya
            return redirect()->route('todo.index')->with('successAdd','Berhasil menambahkan data ToDo!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function show(Todo $todo)
    {
        //menampilkan satu data
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //menampilkan form edit data
        //ambil data dari db yang idnya sama dengan id yang dikirim dari routenya
        $todo = Todo::Where('id', $id)->first();
        // lalu tampilkan halaman dari view edit dengan mengirim data yang ada di variable todo
        return view('dashboard.edit', compact('todo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|min:3',
            'date' => 'required',
            'description' => 'required|min:8',
        ]);
        Todo::where('id', $id)->update([
            'title' => $request->title,
            'description' => $request->description,
            'date' => $request->date,
            'status' => 0,
            'user_id' => Auth::user()->id,
        ]);
        return redirect('/todo/')->with('successUpdate', 'Data berhasil diperbaharui!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // parameter $id akan mengambil data dari path dinamis {id}
        // cari data yang isian colmn id nya sama dengan $id yang dikirim ke path dinamis
        // kalau ada, ambil terus hapus datanya
        Todo::where('id', '=',$id)->delete();
        // kalau berhasil, bakal dibalikin ke halaman list todo dengan pemberitahuan
        return redirect()->route('todo.index')->with('successDelete', 'Berhasil menghapus data ToDo!');
    }
}
