<?php

namespace Admin\Models\Auth;


/*
*
* name AdminUser.php
* author Yuanchang
* date  2017.04.23
*/

use Admin\Traits\AdminAuth;
use Admin\Traits\PermissionTree;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Cache;
use Yuansir\Toastr\Facades\Toastr;

class AdminUser extends User
{

    use PermissionTree, AdminAuth;

    protected $table = 'admin_users';

    protected $fillable = [
        'username', 'password', 'name', 'email', 'avatar'
    ];

    protected $hidden = [
        'remember_token'
    ];

    public $rules = [
        'username' => 'required|max:190',
        'password' => 'required|confirmed',
        'name' => 'required|max:255',
    ];

    public $messages = [
    ];


    public function roles()
    {
        return $this->belongsToMany('Admin\Models\Auth\Role', 'admin_role_users', 'user_id', 'role_id');
    }


    public function permissions()
    {
        return $this->belongsToMany('Admin\Models\Auth\Permission', 'admin_user_permissions', 'user_id', 'permission_id');
    }


    public function createOrUpdate(array $data, AdminUser $user = null)
    {
        // 上传头像 20190520

        if (isset($data['avatar']) && !empty($data['avatar'])) {
            $file = $data['avatar'];
            if ($file && $file->isValid()){
                $url_path = 'adminAvatar';
                $rule = ['jpg', 'png'];
                $clientName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                if (!in_array($extension, $rule)) {
                    return false;
                }
                $newName = md5(date("Y-m-d H:i:s") . $clientName) . "." . $extension;
                $file->move($url_path, $newName);
                $avatarPath = '/'. $url_path . '/' . $newName;
            } else {
                return redirect()->back()->withErrors("头像文件校验错误");
            }
        }else {
            $avatarPath = '';
        }
        if ($user) {
            // 不修改密码
            if (empty($data["password"]))
                $data["password"] = $user->getAuthPassword();
            else
                $data ['password'] = bcrypt($data['password']);
            $data['avatar'] = $avatarPath;
            $user->fill($data)->save();
            return $user;
        } else {
            $this->name = $data['name'];
            $this->password = bcrypt($data['password']);
            $this->username = $data['username'];
            $this->avatar = $avatarPath;
            $this->save();
            return $this;
        }
    }

    /**
     * @desc 用户的权限
     * @author Yuanchang (yuanchang.xu@outlook.com)
     * @since 2018/4/8
     * @return array|bool
     */
    public function userPermissions()
    {
        //if (Cache::has($this->id . ':permissions')) {
        //    $permissions = json_decode(Cache::get($this->id . ':permissions'), true);
        //    return $permissions;
        //}

        $roles = $this->roles()->with("permissions")->get();

        $permissions = [];

        foreach ($roles as $role) {
            $permissions += $role->permissions->toArray();
        }

        //Cache::forever($this->id . ':permissions', json_encode($permissions));
        return $permissions;
    }

    /**
     * @desc 更新用户权限
     * @author Yuanchang (yuanchang.xu@outlook.com)
     * @since 2018/4/8
     */
    public function updateUserPermissions()
    {
        Cache::forget($this->id . ':permissions');
        return $this->userPermissions();
    }

    /**
     * @desc 删除用户权限缓存
     * @author Yuanchang (yuanchang.xu@outlook.com)
     * @since 2018/4/8
     */
    public function destroyUserPermissions()
    {
        Cache::forget($this->id . ':permissions');
    }

    /**
     * @desc 用户的菜单
     * @author Yuanchang (yuanchang.xu@outlook.com)
     * @since 2018/4/4
     * @return array|bool|mixed
     */
    public function userMenus()
    {
        $permissionTree = $this->createPermissionTreeCache();
        //dump($permissionTree);
        if ($this->isRoot($this)) {
            return $permissionTree;
        }

        $hasPermission = array_pluck($this->userPermissions(), "parent_id", "id");
        //dump($hasPermission);
        foreach ($permissionTree as $key => $item) {
            if (!in_array($item["id"], $hasPermission))
                unset($permissionTree[$key]);
            if (!empty($item["child"]))
                foreach ($item["child"] as $kk => $c)
                    if (!array_key_exists($c["id"], $hasPermission))
                        unset($permissionTree[$key][$kk]);
        }
        return $permissionTree;
    }
    public function userTopMenus()
    {
        $permissionTree = $this->createTopPermissionTreeCache();

        if ($this->isRoot($this)) {
            return $permissionTree;
        }
        //dump($this->userPermissions());
        $hasPermission = array_pluck($this->userPermissions(), "parent_id", "id");

        //dump($permissionTree);
        //dump($hasPermission);
        foreach ($permissionTree as $key => $item) {
            if (!in_array($item["id"], $hasPermission))
                unset($permissionTree[$key]);
            /*if (!empty($item["child"]))
                foreach ($item["child"] as $kk => $c)
                    if (!array_key_exists($c["id"], $hasPermission))
                        unset($permissionTree[$key][$kk]);*/
        }
        //  dump($permissionTree);
        return $permissionTree;
    }
}