<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Datatable\OrdersDatatable;
use App\Repositories\OrderStatusRepository;

class ReclamationController extends Controller
{
    public function index()
    {
        return view('front.reclamations.index');
    }

    public function datatable(OrdersDatatable $ordersDatatable)
    {
        $idStatusReclamation = (new OrderStatusRepository())->getIdsStatusComplaining();
        $ordersDatatable->setQuery(
            $ordersDatatable->getOrderQuery()
                ->where('status_id', $idStatusReclamation)
                ->orderBy('updated_at', 'DESC')
                ->orderBy('id', 'DESC'));

        return $ordersDatatable->datatable();
    }
}
