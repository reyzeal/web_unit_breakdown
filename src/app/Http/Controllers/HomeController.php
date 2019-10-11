<?php

namespace App\Http\Controllers;

use App\Log;
use App\Unit;
use Illuminate\Http\Request;

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
