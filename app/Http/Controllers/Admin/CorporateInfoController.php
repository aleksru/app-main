<?php


namespace App\Http\Controllers\Admin;


use Illuminate\Http\Request;

class CorporateInfoController
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.corp-info.index');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        foreach ($request->all() as $key => $item){
            if(preg_match('/^corporate_[a-z]+/', $key)){
                setting([str_replace('_', '.', $key) => $item])->save();
            }
        }

        return redirect()->route('admin.corporate-info.index');
    }
}