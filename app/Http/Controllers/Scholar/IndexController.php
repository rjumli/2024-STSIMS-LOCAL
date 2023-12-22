<?php

namespace App\Http\Controllers\Scholar;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Scholar\ApiService;
use App\Services\Scholar\ViewService;
use App\Services\Scholar\TruncateService;

class IndexController extends Controller
{
    public $api, $view, $truncate;

    public function __construct(ApiService $api, ViewService $view, TruncateService $truncate)
    {
        $this->api = $api;
        $this->view = $view;
        $this->truncate = $truncate;
    }

    public function index(Request $request){
        $type = $request->type;
        switch($type){
            case 'lists':
               return $this->view->lists($request);
            break;
            case 'statistics':
                return $this->statistics($request);
            break;
            case 'search':
                return $this->search($request);
            break;
            case 'print':
                return $this->print($request);
            break;
            default : 
            return inertia('Modules/Scholars/Lists/Index',$this->view->statistics());
        }
    }

    public function store(Request $request){
        $type = $request->type;
        switch($type){
            case 'truncate':
               return $this->truncate->truncate($request);
            break;
            case 'api':
                return $this->api->option($request);
            break;
            case 'update':
                return $this->updating($request);
            break;
            case 'course':
                return $this->course($request);
            break;
        }
    }
}
