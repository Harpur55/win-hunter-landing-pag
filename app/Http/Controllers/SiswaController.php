<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\SiswaExport;
use App\Imports\SiswaImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;

class SiswaController extends Controller
{
    //
   public function export(){
         return \Excel::download(new \App\Exports\Export, 'data_.xlsx');
   }
    public function showImportForm()
    {
        return view('siswa.import'); // Buat view 'anggota/import.blade.php'
    }
     public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048', // Validasi file Excel
        ]);

        try {
            Excel::import(new SiswaImport, $request->file('file'));
            Session::flash('success', 'Data siswa berhasil diimpor!');
            return redirect()->back(); // Kembali ke halaman sebelumnya
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            $errorMessage = '';
            foreach ($failures as $failure) {
                $errorMessage .= "Baris " . $failure->row() . ": " . implode(", ", $failure->errors()) . ". ";
            }
            Session::flash('error', 'Gagal mengimpor data: ' . $errorMessage);
            return redirect()->back()->withErrors($failures);
        } catch (\Exception $e) {
            Session::flash('error', 'Terjadi kesalahan saat mengimpor data: ' . $e->getMessage());
            return redirect()->back();
        }
    }

}
