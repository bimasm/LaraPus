<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Anggota;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Redirect;
use Auth;
use DB;
use RealRashid\SweetAlert\Facades\Alert;

class AnggotaController extends Controller
{
    

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(Auth::user()->level == 'user') {
            
            return redirect()->to('/');
        }

        $datas = Anggota::get();
        return view('anggota.index', compact('datas'));
    }
    

    
    public function create()
    {
        if(Auth::user()->level == 'user') {
           
            return redirect()->to('/');
        }

        $users = User::WhereNotExists(function($query) {
                        $query->select(DB::raw(1))
                        ->from('anggota')
                        ->whereRaw('anggota.user_id = users.id');
                     })->get();
        return view('anggota.create', compact('users'));
    }

    
    public function store(Request $request)
    {
        $count = Anggota::where('npm',$request->input('npm'))->count();

        if($count>0){
            Session::flash('message', 'Already exist!');
            Session::flash('message_type', 'danger');
            return redirect()->to('anggota');
        }

        $this->validate($request, [
            'nama' => 'required|string|max:255',
            'npm' => 'required|string|max:20|unique:anggota'
        ]);

        Anggota::create($request->all());

        alert()->success('Berhasil.','Data telah ditambahkan!');
        return redirect()->route('anggota.index');

    }

   
    public function show($id)
    {
        if((Auth::user()->level == 'user') && (Auth::user()->id != $id)) {
               
                return redirect()->to('/');
        }

        $data = Anggota::findOrFail($id);

        return view('anggota.show', compact('data'));
    }

    
    public function edit($id)
    {   
        if((Auth::user()->level == 'user') && (Auth::user()->id != $id)) {
                
                return redirect()->to('/');
        }

        $data = Anggota::findOrFail($id);
        $users = User::get();
        return view('anggota.edit', compact('data', 'users'));
    }

    
    public function update(Request $request, $id)
    {
        Anggota::find($id)->update($request->all());

        alert()->success('Berhasil.','Data telah diubah!');
        return redirect()->to('anggota');
    }

    public function destroy($id)
    {
        Anggota::find($id)->delete();
        alert()->success('Berhasil.','Data telah dihapus!');
        return redirect()->route('anggota.index');
    }
}
