<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Buku;
use Carbon\Carbon;
use Session;
use Illuminate\Support\Facades\Redirect;
use Auth;
use DB;
use Excel;
use RealRashid\SweetAlert\Facades\Alert;

class BukuController extends Controller
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

        $datas = Buku::get();
        return view('buku.index', compact('datas'));
    }

    public function create()
    {
        if(Auth::user()->level == 'user') {
            
            return redirect()->to('/');
        }

        return view('buku.create');
    }

    public function format()
    {
        $data = [['judul' => null, 'isbn' => null, 'pengarang' => null, 'penerbit' => null, 'tahun_terbit' => null, 'jumlah_buku' => null, 'deskripsi' => null, 'lokasi' => 'rak1/rak2/rak3']];
            $fileName = 'format-buku';
            

        $export = Excel::create($fileName.date('Y-m-d_H-i-s'), function($excel) use($data){
            $excel->sheet('buku', function($sheet) use($data) {
                $sheet->fromArray($data);
            });
        });

        return $export->download('xlsx');
    }

    public function import(Request $request)
    {
        $this->validate($request, [
            'importBuku' => 'required'
        ]);

        if ($request->hasFile('importBuku')) {
            $path = $request->file('importBuku')->getRealPath();

            $data = Excel::load($path, function($reader){})->get();
            $a = collect($data);

            if (!empty($a) && $a->count()) {
                foreach ($a as $key => $value) {
                    $insert[] = [
                            'judul' => $value->judul, 
                            'isbn' => $value->isbn, 
                            'pengarang' => $value->pengarang, 
                            'penerbit' => $value->penerbit,
                            'tahun_terbit' => $value->tahun_terbit, 
                            'jumlah_buku' => $value->jumlah_buku, 
                            'deskripsi' => $value->deskripsi, 
                            'lokasi' => $value->lokasi,
                            'cover' => NULL];

                    Buku::create($insert[$key]);
                        
                    }
                  
            };
        }
        alert()->success('Berhasil.','Data telah diimport!');
        return back();
    }

    
    public function store(Request $request)
    {
        $this->validate($request, [
            'judul' => 'required|string|max:255',
            'isbn' => 'required|string'
        ]);

        if($request->file('cover')) {
            $file = $request->file('cover');
            $dt = Carbon::now();
            $acak  = $file->getClientOriginalExtension();
            $fileName = rand(11111,99999).'-'.$dt->format('Y-m-d-H-i-s').'.'.$acak; 
            $request->file('cover')->move("images/buku", $fileName);
            $cover = $fileName;
        } else {
            $cover = NULL;
        }

        Buku::create([
                'judul' => $request->get('judul'),
                'isbn' => $request->get('isbn'),
                'pengarang' => $request->get('pengarang'),
                'penerbit' => $request->get('penerbit'),
                'tahun_terbit' => $request->get('tahun_terbit'),
                'jumlah_buku' => $request->get('jumlah_buku'),
                'deskripsi' => $request->get('deskripsi'),
                'lokasi' => $request->get('lokasi'),
                'cover' => $cover
            ]);

        alert()->success('Berhasil.','Data telah ditambahkan!');

        return redirect()->route('buku.index');

    }

    
    public function show($id)
    {
        if(Auth::user()->level == 'user') {
                
                return redirect()->to('/');
        }

        $data = Buku::findOrFail($id);

        return view('buku.show', compact('data'));
    }

    
    public function edit($id)
    {   
        if(Auth::user()->level == 'user') {
                
                return redirect()->to('/');
        }

        $data = Buku::findOrFail($id);
        return view('buku.edit', compact('data'));
    }

    
    public function update(Request $request, $id)
    {
        if($request->file('cover')) {
            $file = $request->file('cover');
            $dt = Carbon::now();
            $acak  = $file->getClientOriginalExtension();
            $fileName = rand(11111,99999).'-'.$dt->format('Y-m-d-H-i-s').'.'.$acak; 
            $request->file('cover')->move("images/buku", $fileName);
            $cover = $fileName;
        } else {
            $cover = NULL;
        }

        Buku::find($id)->update([
             'judul' => $request->get('judul'),
                'isbn' => $request->get('isbn'),
                'pengarang' => $request->get('pengarang'),
                'penerbit' => $request->get('penerbit'),
                'tahun_terbit' => $request->get('tahun_terbit'),
                'jumlah_buku' => $request->get('jumlah_buku'),
                'deskripsi' => $request->get('deskripsi'),
                'lokasi' => $request->get('lokasi'),
                'cover' => $cover
                ]);

        alert()->success('Berhasil.','Data telah diubah!');
        return redirect()->route('buku.index');
    }

   
    public function destroy($id)
    {
        Buku::find($id)->delete();
        alert()->success('Berhasil.','Data telah dihapus!');
        return redirect()->route('buku.index');
    }
}
