<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LangController extends Controller
{
    public  function index ($lang){
         app()->setLocale(Session::get('locale'));
        echo trans('message.welcome');
    }
}
