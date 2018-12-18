<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;

Route::get('api/:version/login', 'api/:version.Login/login');
Route::get('api/:version/grade', 'api/:version.Grade/grade');
Route::get('api/:version/timetable', 'api/:version.Timetable/timetable');
Route::get('api/:version/reset', 'api/:version.ResetPassword/resetPassword');
Route::get('api/:version/test', 'api/:version.TestLogin/testLogin');
Route::get('api/:version/change', 'api/:version.ChangePassword/changePassword');
Route::get('api/:version/mail', 'api/:version.Mail/email');
Route::get('api/:version/levelGrade', 'api/:version.LevelGrade/levelGrade');
Route::get('api/:version/evaluate', 'api/:version.TeachingEvaluation/teachingEvaluation');
Route::get('api/:version/testGrade', 'api/:version.TestGrade/testGrade');
