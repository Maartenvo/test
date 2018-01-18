<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Interfaces\RequestHandler;
use App\Role;
use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller {

    /**
     * UserController constructor.
     */
    public function __construct(RequestHandler $requestHandler) {
        parent::__construct($requestHandler);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|RedirectResponse|\Illuminate\View\View
     */
    public function overview() {
        try {
            $users = User::with('roles')->orderBy('id')->paginate(20);
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'There was an error',
                'information' => $exception->getMessage()
            ];

            return redirect()->back()->with('alerts', $alerts);
        }

        return view('gmt.users.overview', [
            'primaryHeading' => 'GMT Users',
            'secondaryHeading' => 'Overview',
            'users' => $users
        ]);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showCreate() {
        try {
            $roles = Role::all();
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The available user roles could not be fetched',
                'information' => $exception->getMessage()
            ];

            return redirect()->back()->with('alerts', $alerts);
        }

        return view('gmt.users.create', [
            'primaryHeading' => 'GMT Users',
            'secondaryHeading' => 'Create',
            'roles' => $roles
        ]);
    }

    /**
     * @param CreateUserRequest $request
     * @return RedirectResponse
     */
    public function create(CreateUserRequest $request) {
        \DB::beginTransaction();
        try {
            /** @var User $user */
            $user = User::create([
                        'name' => $request->getName(),
                        'email' => $request->getEmail(),
                        'password' => bcrypt($request->getPassword())
            ]);

            $user->roles()->attach($request->getRole());

            \DB::commit();

            $alerts[] = [
                'type' => 'success',
                'message' => 'The user [ID: ' . $user->getId() . '] was created successfully',
            ];
        } catch (\Exception $exception) {
            \DB::rollBack();

            $alerts[] = [
                'type' => 'error',
                'message' => 'The user could not be created',
                'information' => $exception->getMessage()
            ];
        }


        return redirect()->back()->with('alerts', $alerts)->withInput(Input::all());
    }

    /**
     * @param Request $request
     * @param int $userId
     * @return RedirectResponse
     */
    public function delete(Request $request, int $userId) {
        try {
            User::destroy($userId);

            $alerts[] = [
                'type' => 'success',
                'message' => 'The user with [ID:' . $userId . '] was deleted successfully',
            ];
        } catch (\Exception $exception) {
            $alerts[] = [
                'type' => 'error',
                'message' => 'The user could not be deleted',
                'information' => $exception->getMessage()
            ];
        }

        return redirect()->back()->with('alerts', $alerts);
    }

}
