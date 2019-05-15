<?php

namespace Admin\Middleware;

/*
* name MenuMiddleware.php
* user Yuanchang.xu
* date 2017/4/28
*/

use Closure;
use Admin\Traits\AdminAuth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;

class MenuMiddleware
{
    use AdminAuth;

    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('admin')->user();
        if (!$user) return redirect(route('admin.login'));
        $menus = $user->userMenus();
        $request->attributes->set("permissions", self::buildPermissionTree($menus));//左侧菜单
        $top_menus = $user->userTopMenus();
//        dump($top_menus);
        $request->attributes->set("top_permissions", self::buildTopPermissionTree($top_menus));//顶部菜单
        return $next($request);

    }

    private static function buildPermissionTree(array $menus): string
    {
        $html = "";
        $current = Route::currentRouteName();
        foreach ($menus as $index => $permission) {
            if ($permission['top_visit'] == 1){
                continue;
            }
            if (self::matchRoute($current, $permission["uri"]))
                $html .= '<li class="active">';
            else
                $html .= '<li>';

            $html .= '<a href="#">';
            $html .= '<i class="fa ' . $permission["icon"] . '"></i>';
            $html .= '<span>' . $permission["name"] . '</span>';
            $html .= '<i class="fa fa-angle-left pull-right"></i>';
            $html .= '</a>';
            $html .= '<ul class="treeview-menu">';

            if (!empty($permission["child"])) {
                foreach ($permission["child"] as $c) {
                    if ($c["uri"] == $current)
                        $html .= '<li class="active">';
                    else
                        $html .= '<li>';
                    $html .= '<a href="' . URL::route($c["uri"]) . '">';
                    $html .= '<i class="fa ' . $c["icon"] . '"></i>';
                    $html .= '<span>' . $c["name"] . '</span>';
                    $html .= '</a>';
                    $html .= '</li>';
                }
            }
            $html .= '</ul>';
            $html .= '</li>';
        }
        return $html;
    }
    private static function buildTopPermissionTree(array $menus): string
    {
        $html = "";
        $current = Route::currentRouteName();
        foreach ($menus as $index => $permission) {
            /*if (self::matchRoute($current, $permission["uri"]))
                $html .= '<li role="presentation" class="active">';
            else
                $html .= '<li  role="presentation">';
            */
            //20190515改成完全匹配
            if($current == $permission["uri"]) {
                $html .= '<li role="presentation" class="active">';
            } else {
                $html .= '<li  role="presentation">';
            }
            $html .= '<a href="' . URL::route($permission["uri"]) . '">'.$permission["name"].'</a></li>';
        }
        return $html;
    }

    private static function matchRoute(string $current, string $route)
    {
        $current = explode(".", $current);
        $cnt = count($current);
        if ($cnt <= 1) {
            return false;
        }
        if ($cnt <= 2) {
            $pattern = '/^'.$current[0].'\.\w+/';
            return preg_match($pattern, $route);
        }

        $pattern = '/\w+\.'.$current[1].'\..*/';
        return preg_match($pattern, $route);
    }
}