<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DB;
use App\Http\Requests\CreateUserRequest;
use App\Services\UserService;
use App\Enum\UserStatus;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Http;
use App\Models\ApiSetting;
use App\Models\Transaction;


class TransactionController extends Controller
{
    public function transactions(Request $request){
        
        $get_transactions = Transaction::with(['user','subscription'])
                                         ->where([
                                            ['user_id', '=', auth()->id()],
                                            ['remarks', '=', 'Subscription Payment']
                                         ])
                                         ->orderBy('id','desc')
                                         ->paginate(10);

        if ($request->ajax()) {
            $viewTransaction = view('dashboard.pages.tranxdata', compact('get_transactions'))->render();
            
            return response()->json([
                'newhtmltransaction' => $viewTransaction
            ]);
        }                                 
        return view('dashboard.pages.transaction',compact('get_transactions'));
    }

}
