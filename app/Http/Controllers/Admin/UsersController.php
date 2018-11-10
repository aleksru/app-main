<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.users.index');
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('admin.users.form');
    }

    /**
     * @param UserRequest $userRequest
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(UserRequest $userRequest)
    {
        $data = $userRequest->validated();
        $data['password'] = Hash::make($data['password']);

        return redirect()->route('admin.users.edit', User::create($data)->id)->with(['success' => 'Успешно создан!']);
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('admin.users.form', ['user' => $user]);
    }

    /**
     * @param UserRequest $userRequest
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserRequest $userRequest, User $user)
    {
       $data = array_diff($userRequest->validated(), array(''));

       if (isset($data['password'])) {
           $data['password'] = Hash::make($data['password']);
       }
        $user->update($data);

        return redirect()->route('admin.users.edit', $user->id)->with(['success' => 'Успешно обновлен!']);
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json(['message' => 'Пользователь удален']);
    }

    /**
     * @return json
     */
    public function datatable()
    {
        return datatables() ->of(User::query())
            ->editColumn('actions', function (User $user) {
                return view('datatable.actions', [
                        'edit' => [
                            'route' => route('admin.users.edit', $user->id),
                        ],
                        'delete' => [
                            'id' => $user->id,
                            'name' => $user->name,
                            'route' => route('admin.users.destroy', $user->id)
                        ]
                ]);
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
}
