<?php

namespace App\Http\Controllers;


use App\ClientCall;
use App\Enums\MangoCallEnums;
use App\Enums\MangoResultCodes;
use App\MissedCall;
use App\Models\Operator;
use App\Repositories\OrderRepository;
use App\Services\Calls\Missed\CounterMissed;
use App\Services\Mango\Commands\Callback;
use App\Services\Mango\MangoService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ClientCallController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $this->authorize('view', ClientCall::class);
        $user =  Auth::user();
        $operator = ($user->isOperator() && $user->account) ? $user->account : null;
        $complaintNumbers = MangoCallEnums::COMPLAINT_NUMBERS;

        return view('front.calls.index', compact('operator', 'complaintNumbers'));
    }

    public function callback(Operator $operator, Request $request)
    {
        if($operator->extension === null){
            return response()->json(['message' => 'Не назначен внутренний номер в системе. Обратитесь к системному администратору'], 422);
        }
        $callback = new Callback();
        $uuid = Str::uuid();
        $callback->command_id($uuid)
                ->line_number($request->get('store_phone'))
                ->extension($operator->extension)
                ->to_number($request->get('phone'));
        $mangoService = new MangoService();
        try {
            $res = $mangoService->callback($callback);
            MissedCall::excludeOnNumber($request->get('phone'));
            return response()->json([
                'command_id' => $res['command_id'],
                'result'     => MangoResultCodes::getDescriptionCode($res['result'])
            ]);
        }catch (\Exception $e){
            Log::channel('calls')->error(['Exception', $res, $e]);
            throw new \Exception('Ошибка передачи в Mango');
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCallsByPhone(Request $request)
    {
        $phone = $request->get('phone');
        if(!$phone){
            return response()->json(['error' => 'Не найден номер телефона!'], 422);
        }
        $calls = ClientCall::with('store')->where('from_number', $phone)->orderBy('id', 'DESC')->get();

        return response()->json(['calls' => $calls]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatable(Request $request)
    {
        $toDate = Carbon::today();
        if($request->get('forDate')) {
            $toDate = Carbon::parse($request->get('forDate'));
        }
        $callsIds = MissedCall::query()
            ->whereBetween('created_at', [(clone $toDate)->subHours(3), (clone $toDate)->addDay()])
            ->pluck('client_call_id');
        $calls = ClientCall::with([
            'client.storeComplaints' => function($query){
                return $query->select('store_id');
            },
            'store'
        ])->whereIn('id', $callsIds)
            ->orderBy('call_create_time', 'DESC')
            ->get();

        return response()->json([
            'calls'             => $calls,
            'uniquePhones'      => $calls->sum('is_first'),
            'countSimples'      => CounterMissed::getCountSimplesByDate($toDate),
            'countReclamations' => CounterMissed::getCountReclamationsByDate($toDate)
        ]);
    }

    public function callQueue(Request $request, OrderRepository $orderRepository)
    {
        $freeOperatorOrders = $orderRepository->getFreeOperatorOrders(Carbon::today());
        $ordersCallBacks = $orderRepository->getCallBacksOrders();
        $ordersMissedRecall = $orderRepository->getOrdersForRecall();
        $freeOperatorOrders = $freeOperatorOrders->merge($ordersCallBacks)->merge($ordersMissedRecall);

        if($request->ajax()){
            return response()->json(['orders' => $freeOperatorOrders]);
        }

        return view('front.calls.calls_queue', ['data' => $freeOperatorOrders]);
    }
}
