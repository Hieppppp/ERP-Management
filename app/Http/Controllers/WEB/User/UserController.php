<?php

namespace App\Http\Controllers\WEB\User;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\ProfileRequest;
use App\Http\Requests\User\UpdatePasswordRequest;
use App\Http\Requests\User\UserCreateRequest;
use App\Http\Requests\User\UserUpdateRequest;
use App\Services\Permission\PermissionService;
use App\Services\Permission\PermissionServiceInterface;
use App\Services\User\UserServiceInterface;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected UserServiceInterface $userService;

    protected PermissionServiceInterface $permissionService;

    public function __construct(
        UserServiceInterface $userService,
        PermissionServiceInterface $permissionService
    ) {
        $this->userService = $userService;
        $this->permissionService = $permissionService;
    }

    /**
     * index
     *
     * @return View|Factory
     */
    public function index(): View|Factory
    {
        return view('pages/user/index');
    }

    /**
     * create
     *
     * @return View|Factory
     */
    public function create(): View|Factory
    {
        $data['roles'] = [UserRole::USER, UserRole::ADMIN];
        $data['permissions'] = $this->permissionService->getAll();
        return view('pages/user/create', $data);
    }

    /**
     * store
     *
     * @param  UserCreateRequest $request
     * @return Redirector|RedirectResponse
     */
    public function store(UserCreateRequest $request): Redirector|RedirectResponse
    {
        $data = $request->validated();
        $this->userService->create($data);
        session()->flash('success', __('message.success'));
        return redirect()->to('/user');
    }

    /**
     * show
     *
     * @param  string $id
     * @return View|Factory
     */
    public function show(string $id): View|Factory
    {
        $user = $this->userService->findById($id);
        $activities = $user->activities()->with(['causer' => function ($query) {
            $query->withTrashed();
        }])->orderBy('id', 'desc')->get();
        return view('pages/user/detail', ['user' => $user, 'activities' => $activities]);
    }

    /**
     * edit
     *
     * @param  string $id
     * @return View|Factory|RedirectResponse
     */
    public function edit(string $id): View|Factory|RedirectResponse
    {
        $user = $this->userService->findById($id);

        if ($user->role == UserRole::ADMIN) {
            if (Auth::user()->role != UserRole::SUPPER_ADMIN) {
                return redirect()->to('/user');
            }
        }
        if ($user && $user->permissions) {
            $user['permissions'] = $user->permissions->toArray();
        }
        $data['user'] = $user;
        $data['roles'] = [UserRole::ADMIN, UserRole::USER];
        $data['activities'] = $user->activities()->with(['causer' => function ($query) {
            $query->withTrashed();
        }])->orderBy('id', 'desc')->get();
        $data['permissions'] = $this->permissionService->getAll();
        return view('pages/user/edit', $data);
    }

    /**
     * update
     *
     * @param  UserUpdateRequest $request
     * @param  int $id
     * @return Redirector|RedirectResponse
     */
    public function update(UserUpdateRequest $request, string $id): Redirector|RedirectResponse
    {
        $param = $request->validated();
        $user = $this->userService->findById($id);

        if ($user->role == UserRole::ADMIN) {
            if (Auth::user()->role != UserRole::SUPPER_ADMIN) {
                return redirect()->to('/user');
            }
        }
        $this->userService->update($param, $id);
        session()->flash('success', __('message.success'));
        return redirect()->to('/user');
    }

    /**
     * profile
     *
     * @return View|Factory
     */
    public function profile(): View|Factory
    {
        $data['profile'] = Auth::user();
        return view('pages/user/profile', $data);
    }

    /**
     * update profile
     *
     * @return Redirector|RedirectResponse
     */
    public function updateProfile(ProfileRequest $request): Redirector|RedirectResponse
    {
        $params = $request->validated();
        $user = Auth::user();
        $user->update($params);
        return redirect()->to('profile')->with('success', __('message.success'));
    }

    /**
     * update profile
     *
     * @return Redirector|RedirectResponse
     */
    public function updatePassword(UpdatePasswordRequest $request): Redirector|RedirectResponse
    {
        $params = $request->validated();
        $user = Auth::user();
        $user->update(['password' => $params['new_password']]);
        return redirect()->to('profile')->with('success', __('message.success'));
    }
}
