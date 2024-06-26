<?php

namespace App\Http\Controllers;

use App\Helpers\ApiFormatter;
use App\Models\Products;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\Catch_;

class ProductsController extends Controller
{

    public function index()
    {
        try {
            //ambil data yg mau ditampilkan
            $data = Products::all()->toArray();

            return ApiFormatter::sendResponse(200, 'success', $data);
        }catch (\Exception $err){
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }

        }

        public function store (Request $request)
        {
            try {
                // validasi
                // 'nama_column' => validasi
                $this->validate($request, [
                    'name' => 'required|min:3',
                    'price' => 'required',
                ]);

                $prosesData = Products::create([
                    'name' => $request->name,
                    'price' => $request->price,
                ]);

                if ($prosesData) {
                    return ApiFormatter::sendResponse(200, 'success', $prosesData);
                } else {
                    return ApiFormatter::sendResponse(400, 'bad request', 'gagal memproses tambah data Products! silahkan coba lagi.');
                }
            }catch (\Exception $err) {
                return ApiFormatter::sendResponse(400,'bad request',$err->getMessage());
        }
    }

    //$id
    public function show($id)
    {
        try {
            $data = Products::where('id',$id)->first();
            //first() : kalau gada, tetep success data nya kosong
            //firstOrFail() : kalau gada, munculnya error
            //find() : mencari berdasarkan primary key (id)
            //where() : mencari column spesific tertentu (nama)

            return ApiFormatter::sendResponse(200,'success',$data);
        }catch(\Exception $err) {
            return ApiFormatter::sendResponse(400,'bad request',$err->getMessage());
    }
  }

  //Request  : data yang dikirim
  // $id : data yang akan di update, dari route{}
  public function update(Request $request,$id)
  {
    try {
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required',
        ]);

        $checkProsess = Products::where('id',$id)->update([
            'name'=>$request->name,
            'price'=>$request->price,
        ]);

        if($checkProsess){
            // ::create([]) : menghasilkan data yang ditambah
            // ::create([]) : menghasikan boolean, jadi buat ambil data terbaru di cari lagi
            $data = Products::where('id',$id)->first();
            return ApiFormatter::sendResponse(200, 'succes', $data);
            }
        } catch(\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $checkProsess = Products::where('id',$id)->delete();
            if ($checkProsess) {
                return ApiFormatter::sendResponse(200, 'succes', 'Berhasil hapus data products!');
            }
        }catch(\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }


    public function trash()
    {
        try{
        //onlyTrashed() : memanggil data sampah/yang sudah dihapus/deleted_at nya terisi
        $data = Products::onlyTrashed()->get();
        return ApiFormatter::sendResponse(200, 'succes', $data);
    }catch(\Exception $err) {
        return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
    }
  }

  public function restore($id)
  {
    try{
        //restore : mengembalikan data spesifik yang dihapus/menghapus deleted_at nya
        $checkRestore = Products::onlyTrashed()->where('id',$id)->restore();

        if ($checkRestore) {
            $data = Products::where('id',$id)->first();
            return ApiFormatter::sendResponse(200,'success',$data);
        }
    }catch(\Exception $err) {
        return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
    }
  }

  public function permanentDelete($id)
  {
    try {
        // forceDelete() : menghapus permanent (hilang juga data di db nya)
        $checkPermanenDelete = Products::onlyTrashed()->where('id', $id)->forceDelete();
        if ($checkPermanenDelete){
            return ApiFormatter::sendResponse(200,'success','Berhasil menghapus permanent data Products!');
        }
    } catch (\Exception $err) {
        return ApiFormatter::sendResponse(400,'bad request',$err->getMessage());
    }
  }
}
