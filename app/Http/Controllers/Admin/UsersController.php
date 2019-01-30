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
        $user = User::create($data);
        $user->roles()->sync(isset($data['roles']) ? $data['roles'] : []);

        return redirect()->route('admin.users.edit', $user->id)->with(['success' => 'Успешно создан!']);
    }

    /**
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(User $user)
    {
        return view('admin.users.form', ['user' => $user, 'optionsAccount' => $user->getRelationByAccount()]);
    }

    /**
     * @param UserRequest $userRequest
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UserRequest $userRequest, User $user)
    {
        $data = $userRequest->validated();

        if (!$data['password']) {
            unset($data['password']);
        }

        if (isset($data['password']) && $data['password']) {
           $data['password'] = Hash::make($data['password']);
        }


        if(array_key_exists('account_id', $data) && $user->group) {
            $modelAccount = $user->getRelationByAccount();
            $modelAccount = $modelAccount::find($data['account_id']);

            if($user->account && $user->account->id !== $data['account_id']) {
                $oldModel = $user->account;
                $oldModel->user_id = null;
                $oldModel->save();
                $modelAccount ? $user->account()->save($modelAccount) : null;
            }

            if(!$user->account){
                $modelAccount ? $user->account()->save($modelAccount) : null;
            }
        }

        if(!isset($data['group_id']) && $user->group) {
            if($user->account) {
                $oldModel = $user->account;
                $oldModel->user_id = null;
                $oldModel->save();
            }
        }

        $user->update($data);
        $user->roles()->sync(isset($data['roles']) ? $data['roles'] : []);

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
