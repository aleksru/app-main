<?php

namespace App\Http\Controllers;

use App\Client;
use App\Http\Requests\ClientRequest;
use App\Order;
use App\Product;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function __construct ()
    {
        $this->middleware('role:read_orders|change_orders');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @param ClientRequest $clientRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ClientRequest $clientRequest)
    {
        Client::create($clientRequest->validated());

        return redirect()->back()->with(['success' => 'Успешно создан!']);
    }

    /**
     * @param Client $client
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Client $client)
    {
        return view('front.client.show', [
            'client' => $client->load([
                'orders' => function($query) {
                    $query->orderBy('created_at', 'desc');
                },
                'calls' => function($query) {
                    $query->orderBy('created_at', 'desc');
                },
                'calls.store:id,name'
            ])
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * @param ClientRequest $clientRequest
     * @param Client $client
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ClientRequest $clientRequest, Client $client)
    {
        $client->update($clientRequest->validated());

        return redirect()->back()->with(['success' => 'Успешно обновлен!']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Создание клиента и заказа
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrderClient(Request $request)
    {
        if (! $request->get('phone')){
            return redirect()->back()->with(['error' => 'Не заполнено поле!']);
        }

        $client = Client::getOnPhone($request->get('phone'))->first();
        if (! $client) {
            $client = Client::create(['phone' => $request->get('phone')]);
        }

        $order = Order::create([
            'client_id' => $client->id,
            'store_text' => '',
            'comment' =>'-',
        ]);

        return redirect()->route('orders.edit', $order->id)->with(['success' => 'Успешно создан!']);
    }
}
