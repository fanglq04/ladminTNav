<?php

namespace Admin\Controllers;


/*
* 
* name IndexController.php
* author Yuanchang
* date ${DATA}
*/

use Illuminate\Support\Facades\Cache;
use Admin\Models\LoginLog;
use Illuminate\Http\Request;

class IndexController extends BaseController
{
    public function index()
    {
        $loginLogs = LoginLog::paginate(10);
        if (view()->exists('admin.index')) {
            $tpl = 'admin.index';
        } else {
            $tpl = 'admin::index';
        }
        return view($tpl, compact('loginLogs'));
    }
}