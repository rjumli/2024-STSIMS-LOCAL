<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\StaffService;
use App\Http\Requests\StaffRequest;
use App\Traits\HandlesTransaction;

class ListController extends Controller
{   
    use HandlesTransaction;
    public $staff;

    public function __construct(StaffService $staff)
    {
        $this->staff = $staff;
    }

    public function index(Request $request){
        $option = $request->option;
        switch($option){
            case 'lists':
               return $this->staff->lists($request);
            break;
            default : 
            return inertia('Modules/Staffs/Lists/Index');
        }
    }

    public function store(StaffRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            return $this->staff->saveStaff($request);
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }

}
