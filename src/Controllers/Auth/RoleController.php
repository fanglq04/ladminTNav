<?php

namespace Admin\Controllers\Auth;

/*
* name RoleController.php
* user Yuanchang.xu
* date 2017.04.23
*/

use Admin\Events\UserCacheEvent;
use Admin\Models\Auth\AdminUser;
use Admin\Models\Auth\Role;
use Illuminate\Http\Request;
use Admin\Traits\PermissionTree;
use Admin\Controllers\BaseController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\Rule;

class RoleController extends BaseController
{
    use PermissionTree;


    public function index()
    {

        $roles = Role::paginate(10);
        if (view()->exists('admin.auth.role.index')) {
            $tpl = 'admin.auth.role.index';
        } else {
            $tpl = 'admin::auth.role.index';
        }
        return view($tpl, ['roles' => $roles]);
    }


    public function create()
    {
        // get permission information
        $permissions = $this->createPermissionTreeCache();
        if (view()->exists('admin.auth.role.index')) {
            $tpl = 'admin.auth.role.create';
        } else {
            $tpl = 'admin::auth.role.create';
        }
        return view($tpl, ['permissions' => $permissions]);
    }

    public function store(Request $request)
    {
        $model = new Role();

        $validator = \Validator::make($request->all(), $model->rules, $model->messages);

        if ($validator->fails())
            return redirect()->back()->withErrors($validator->errors());

        if (!$model->fill($request->all())->save())
            return redirect()->back()->withErrors("保存失败");

        if ($request->get('permission_id'))
            $model->permissions()->sync($request->get('permission_id'));

        return redirect(route('roles.index'));
    }


    public function edit($id)
    {
        $role = Role::find($id);
        if (!$role)
            return redirect(route('roles.index'))->withErrors("该角色不存在或已经被删除");
        $permissions = $this->createPermissionTreeCache();
        $role_permissions = $role->permissions()->pluck('permission_id')->toArray();
        if (view()->exists('admin.auth.role.edit')) {
            $tpl = 'admin.auth.role.edit';
        } else {
            $tpl = 'admin::auth.role.edit';
        }
        return view($tpl, ['role' => $role, 'permissions' => $permissions, 'role_permissions' => $role_permissions]);
    }


    public function update($id, Request $request)
    {
        $model = Role::find($id);
        if (!$model)
            return redirect(route('roles.index'))->withErrors("该角色不存在或已经被删除");

        $model->rules["name"] = [
            'name' => 'required',
            Rule::unique("admin_roles")->ignore($model->id),
        ];

        $validator = \Validator::make($request->all(), $model->rules, $model->messages);

        if ($validator->fails())
            return redirect()->back()->withErrors($validator->errors());

        if (!$model->fill($request->all())->save())
            return redirect()->back()->withErrors("修改失败");

        if ($request->get('permission_id'))
            $model->permissions()->sync($request->get('permission_id'));
        else
            $model->permissions()->detach();

        collect($model->users)->each(function (AdminUser $user) {
            $user->updateUserPermissions();
        });
        return redirect(route('roles.index'));
    }


    public function destroy($id)
    {
        $model = Role::find($id);
        if (!$model)
            return redirect(route('roles.index'))->withErrors("该角色不存在或已经被删除");
        try {
            $model->delete();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors($e->getMessage());
        }
        return redirect(route('roles.index'));
    }
}