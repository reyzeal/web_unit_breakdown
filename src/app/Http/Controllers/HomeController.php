<?php

namespace App\Http\Controllers;

use App\Log;
use App\Unit;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function download(Request $request){
        $logs = Log::with('unit')->get();
        $i = 2;

        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();
        $worksheet->setCellValueByColumnAndRow(1, 1, 'No');
        $worksheet->setCellValueByColumnAndRow(2, 1, 'Unit');
        $worksheet->setCellValueByColumnAndRow(3, 1, 'Keterangan');
        $worksheet->setCellValueByColumnAndRow(4, 1, 'Lokasi');
        $worksheet->setCellValueByColumnAndRow(5, 1, 'Jam');
        $worksheet->setCellValueByColumnAndRow(6, 1, 'Status');
        $worksheet->setCellValueByColumnAndRow(7, 1, 'Jam');
        $worksheet->setCellValueByColumnAndRow(8, 1, 'Status');
        $worksheet->setCellValueByColumnAndRow(9, 1, 'Kategori');
        foreach ($logs as $log){
            $worksheet->setCellValueByColumnAndRow(1,$i, $i-1);
            $worksheet->setCellValueByColumnAndRow(2,$i, $log->unit->code);
            $worksheet->setCellValueByColumnAndRow(3,$i, $log->keterangan);
            $worksheet->setCellValueByColumnAndRow(4,$i, $log->location);
            $worksheet->setCellValueByColumnAndRow(5,$i, $log->breakdown);
            $worksheet->setCellValueByColumnAndRow(6,$i, 'B/D');
            $worksheet->setCellValueByColumnAndRow(7,$i, $log->ready);
            $worksheet->setCellValueByColumnAndRow(8,$i, 'ready');
            $worksheet->setCellValueByColumnAndRow(9,$i++, $log->kategori);
        }
        $nama = "Report.xlsx";
        $writer = IOFactory::createWriter($spreadsheet,"Xlsx");
        $writer->save(storage_path("app\\$nama"));
        return response()->download(storage_path("app\\$nama"))->deleteFileAfterSend(true);
    }
    public function addBreakdown(Request $request){
        $unit = Unit::where('code', $request->get('code'))->first();
        if(!$unit) $unit = Unit::create(['code' => $request->get('code')]);
        Log::create([
            'unit_id' => $unit->id,
            'breakdown' => now(),
            'kategori' => $request->kategori,
            'keterangan' => $request->keterangan,
            'location' => $request->location
        ]);

        return redirect()->back();
    }
    public function editBreakdown(Request $request){
        $unit = Unit::where('code', $request->get('code'))->first();
        if(!$unit) $unit = Unit::create(['code' => $request->get('code')]);
        $log = Log::find($request->log);

        $log->fill([
            'unit_id' => $unit->id,
            'breakdown' => now(),
            'kategori' => $request->kategori,
            'location' => $request->location,
            'keterangan' => $request->keterangan
        ]);
        $log->save();

        return redirect()->back();
    }

    public function addReady(Request $request){
        $unit = Unit::where('code', $request->get('code'))->first();
        if(!$unit) $unit = Unit::create(['code' => $request->get('code')]);
        $log = Log::find($request->log);

        $log->fill([
            'unit_id' => $unit->id,
            'ready' => now(),
            'kategori' => $request->kategori,
            'keterangan' => $request->keterangan,
            'location' => $request->location
        ]);

        $log->save();
        return redirect()->back();
    }

    public function resource(Request $request){
        $breakdown = \App\Log::whereNull('ready')->with('unit')->get();
        $ready = \App\Log::whereNotNull('ready')->where('ready','>', now()->subHours(12))->with('unit')->get();

        return response()->json([
            'breakdown' => $breakdown,
            'ready' => $ready,
            'all' => \App\Log::all()
        ],200);
    }
}
