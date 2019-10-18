<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        $filePath = file_exists(storage_path('logs'.DIRECTORY_SEPARATOR.'laravel-'.date("Y-m-d").'.log'));

        if ($filePath) {
            $filePath = storage_path('logs'.DIRECTORY_SEPARATOR.'laravel-'.date("Y-m-d").'.log');
        }

        return view('admin.log.index', ['filePath' => $filePath]);
    }

    public function version()
    {
        $filePath = file_exists(storage_path('version.log'));

        if ($filePath) {
            $filePath = storage_path('version.log');
        }

        return view('front.logs.version', ['filePath' => $filePath]);
    }
}
