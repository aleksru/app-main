<?php


namespace App\Http\Controllers\Logistic;

use App\Enums\FileStatusesEnums;
use App\Http\Controllers\Controller;
use App\Http\Requests\UploadPrice;
use App\Jobs\FileRealizationJob;
use App\Models\FileRealization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class UploadRealizationsController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View
     */
    public function index()
    {
        return view('front.logistic.upload.realization.upload', [
            'files' => File::allFiles(storage_path('logs/uploads'))
        ]);
    }

    public function log($file)
    {
        return Storage::disk('logs')->download('uploads/' . $file);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function upload(UploadPrice $request)
    {
        $origName = $request->file('file')->getClientOriginalName();
        $path = $request->file('file')->store('realizations');
        $file = FileRealization::create(['name' => $origName, 'path' => $path]);
        FileRealizationJob::dispatch($file)->onQueue('files');

        return redirect()->route('logistics.uploads.realizations.index')
                        ->with(['message' => 'Файл отправлен в обработку']);
    }

    public function datatable()
    {
        return datatables() ->of(FileRealization::query())
            ->editColumn('status', function (FileRealization $realization) {
                return FileStatusesEnums::getDesc($realization->status);
            })
            ->editColumn('created_at', function (FileRealization $realization) {
                return $realization->created_at->format('d.m.Y H:i:s');
            })
            ->make(true);
    }
}
