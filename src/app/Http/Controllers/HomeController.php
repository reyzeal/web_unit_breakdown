<?php

namespace App\Http\Controllers;

use App\Log;
use App\Unit;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $user = Auth::user();
        return view('home')->with(['user' => $user]);
    }
    public function download(Request $request){
        $a = \Carbon\Carbon::createFromFormat('d-m-Y H:i',$request->get('start'));
        $b = \Carbon\Carbon::createFromFormat('d-m-Y H:i',$request->get('end'));
        if($a > $b){
            $c = $b;
            $b = $a;
            $a = $c;
        }
        $logs = Log::whereBetween('created_at',[$a,$b])->with('unit')->get();
        $i = 1;

        $spreadsheet = new Spreadsheet();
        $worksheet = $spreadsheet->getActiveSheet();
        $worksheet->setCellValueByColumnAndRow(1, $i, 'Data Breakdown');
        $worksheet->setCellValueByColumnAndRow(6, $i, 'Filter Date :');
        $worksheet->setCellValueByColumnAndRow(7, $i, $a->format('d-m-Y H:i'));
        $worksheet->setCellValueByColumnAndRow(8, $i, 'until');
        $worksheet->setCellValueByColumnAndRow(9, $i, $b->format('d-m-Y H:i'));
        $worksheet->setCellValueByColumnAndRow(10, $i, 'Server Timezone:');
        $worksheet->setCellValueByColumnAndRow(11, $i++, 'Asia/Makassar');
        $worksheet->setCellValueByColumnAndRow(1, $i, 'No');
        $worksheet->setCellValueByColumnAndRow(2, $i, 'Unit');
        $worksheet->setCellValueByColumnAndRow(3, $i, 'Keterangan');
        $worksheet->setCellValueByColumnAndRow(4, $i, 'Lokasi');
        $worksheet->setCellValueByColumnAndRow(5, $i, 'Tanggal');
        $worksheet->setCellValueByColumnAndRow(6, $i, 'Jam');
        $worksheet->setCellValueByColumnAndRow(7, $i, 'Status');
        $worksheet->setCellValueByColumnAndRow(8, $i, 'Tanggal');
        $worksheet->setCellValueByColumnAndRow(9, $i, 'Jam');
        $worksheet->setCellValueByColumnAndRow(10, $i, 'Status');
        $worksheet->setCellValueByColumnAndRow(11, $i++, 'Kategori');
        foreach ($logs as $log){
            $worksheet->setCellValueByColumnAndRow(1,$i, $i-2);
            $worksheet->setCellValueByColumnAndRow(2,$i, $log->unit->code);
            $worksheet->setCellValueByColumnAndRow(3,$i, $log->keterangan);
            $worksheet->setCellValueByColumnAndRow(4,$i, $log->location);
            $worksheet->setCellValueByColumnAndRow(5,$i, \Carbon\Carbon::parse($log->breakdown)->format('d-m-Y'));
            $worksheet->setCellValueByColumnAndRow(6,$i, \Carbon\Carbon::parse($log->breakdown)->format('H:i'));
            $worksheet->setCellValueByColumnAndRow(7,$i, 'B/D');
            $worksheet->setCellValueByColumnAndRow(8,$i, \Carbon\Carbon::parse($log->ready)->format('d-m-Y'));
            $worksheet->setCellValueByColumnAndRow(9,$i, \Carbon\Carbon::parse($log->ready)->format('H:i'));
            $worksheet->setCellValueByColumnAndRow(10,$i, 'ready');
            $worksheet->setCellValueByColumnAndRow(11,$i++, $log->kategori);
        }
        $nama = "Report.xlsx";
        $writer = IOFactory::createWriter($spreadsheet,"Xlsx");
        $writer->save(storage_path("app/$nama"));
        return response()->download(storage_path("app/$nama"))->deleteFileAfterSend(true);
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
        if(Auth::user()->level==2){
            $log->fill([
                'unit_id' => $unit->id,
                'kategori' => $request->kategori,
                'location' => $request->location,
                'keterangan' => $request->keterangan,
                'checked' => false
            ]);
        }else{
            $jam = explode(':',$request->breakdown);
            $time = new Carbon($log->breakdown);
            $time->hour = $jam[0];
            $time->minute = $jam[1];
            $log->fill([
                'unit_id' => $unit->id,
                'breakdown' => $time,
                'kategori' => $request->kategori,
                'location' => $request->location,
                'keterangan' => $request->keterangan,
                'checked' => false
            ]);
        }

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
            'location' => $request->location,
            'checked' => false
        ]);

        $log->save();
        return redirect()->back();
    }

    public function resource(Request $request){
        $breakdown = \App\Log::whereNull('ready')->with('unit')->get();
        $ready = \App\Log::whereNotNull('ready')->where('ready','>', now()->subHours(12))->with('unit')->get();
        $a = $request->get('start')?\Carbon\Carbon::createFromFormat('d-m-Y H:i',$request->get('start')):now()->subDay();
        $b = $request->get('end')?\Carbon\Carbon::createFromFormat('d-m-Y H:i',$request->get('end')):now();
        if($a > $b){
            $c = $b;
            $b = $a;
            $a = $c;
        }
        return response()->json([
            'breakdown' => $breakdown,
            'ready' => $ready,
            'all' => \App\Log::whereBetween('created_at',[$a,$b])->orWhereBetween('breakdown',[$a,$b])->orWhereBetween('ready',[$a,$b])->get(),
            'startDate' => $a->format('d-m-Y'),
            'startH' => $a->format('H'),
            'startM' => $a->format('i'),
            'endDate' => $b->format('d-m-Y'),
            'endH' => $b->format('H'),
            'endM' => $b->format('i'),
        ],200);
    }

    public function notifikasi(Request $request){
        $log = Log::find($request->log);
        if (Auth::user()->level == 2 && !$log->ready){
            $log->checked = true;
            $log->save();
        }elseif(Auth::user()->level != 2 && $log->ready){
            $log->checked = true;
            $log->save();
        }
    }
}
