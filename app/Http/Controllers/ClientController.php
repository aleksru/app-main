<?php

namespace App\Http\Controllers;

use App\Client;
use App\Http\Requests\ClientRequest;
use App\Models\ClientPhone;
use App\Order;
use App\Product;
use App\Repositories\ClientRepository;
use Illuminate\Http\Request;

class ClientController extends Controller
{
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
        $this->authorize('update', Order::class);
        Client::create($clientRequest->validated());

        return redirect()->back()->with(['success' => 'Успешно создан!']);
    }

    /**
     * @param Client $client
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(Client $client)
    {
        $this->authorize('view', Order::class);

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
        $this->authorize('update', Order::class);
        $client->update($clientRequest->validated());

        $mainPhone = false;
        if($clientRequest->get('main-phone')) {
            foreach($clientRequest->get('main-phone') as $key => $value) {
                $mainPhone = $key;
            }
        }

        $additionalPhones = $clientRequest->get('additional_phones');
        foreach($additionalPhones as $key => $additionalPhone) {
            if ($key === 'new' && $additionalPhone) {
                $valid = ClientPhone::where('phone', preg_replace('/[^0-9]/', '', $additionalPhone))->count();
                if($valid !== 0) {
                    return redirect()->back()->with(['error' => $additionalPhone.' уже существует!']);
                }

                $client->additionalPhones()->create(['phone' => $additionalPhone]);
                continue;
            }
            $clientPhone = ClientPhone::find($key);
            if($clientPhone) {
                if (!$additionalPhone){
                    $clientPhone->delete();
                    continue;
                }

                $valid = ClientPhone::where('phone', preg_replace('/[^0-9]/', '', $additionalPhone))->where('id', '<>', $clientPhone->id)->count();
                if($valid !== 0) {
                    return redirect()->back()->with(['error' => $additionalPhone.' уже существует!']);
                }

                $clientPhone->update(['phone' => $additionalPhone, 'main' => ($clientPhone->id === $mainPhone ? 1 : 0)]);
            }
        }

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
     * @param ClientRepository $clientRepository
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createOrderClient(Request $request, ClientRepository $clientRepository)
    {
        $this->authorize('update', Order::class);

        if (! $request->get('phone')){
            return redirect()->back()->with(['error' => 'Не заполнено поле!']);
        }

        $client = $clientRepository->getClientByPhone($request->get('phone'));

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
