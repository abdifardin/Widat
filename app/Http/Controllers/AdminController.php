<?php
/**
 * Created by PhpStorm.
 * User: mnvoh
 * Date: 11/9/15
 * Time: 6:54 PM
 */

namespace App\Http\Controllers;


class AdminController extends Controller
{
	public function home()
	{
		return view('admin.home');
	}
}