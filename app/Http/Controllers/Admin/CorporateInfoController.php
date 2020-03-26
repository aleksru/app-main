<?php


namespace App\Http\Controllers\Admin;


use App\Enums\TextTypeEnums;
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

    public function delivery()
    {
        return view('admin.corp-info.delivery');
    }

    public function deliveryStore(Request $request)
    {
        foreach ($request->all() as $key => $item){
            if(preg_match('/^delivery_[a-z]+/', $key) && ! empty($item)){
                setting([str_replace('_', '.', $key) => $item])->save();
            }
        }

        return redirect()->route('admin.delivery-info.index');
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

    public function indexText()
    {
        return view('admin.corp-info.warranty');
    }

    public function storeText(Request $request)
    {
        if($request->get('content') != null){
            $type = $request->get('type');
            setting([TextTypeEnums::ROOT . '.' . $type => $request->get('content')])->save();
        }

        return redirect()->route('admin.warranty-text.index');
    }
}