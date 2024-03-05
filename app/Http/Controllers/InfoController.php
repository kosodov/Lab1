<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class InfoController extends Controller
{
    public function phpInfo()
    {
        return response()->json(['php_version' => phpversion()]);
    }

    public function clientInfo(Request $request)
    {
        return response()->json(['ip' => $request->ip(), 'user_agent' => $request->userAgent()]);
    }

    public function databaseInfo()
    {
        // Ваш код для получения информации о базе данных
        $databaseVersion = DB::select('SELECT version() as version');

        return response()->json(['database_version' => $databaseVersion]);
    }
}
