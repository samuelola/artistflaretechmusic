<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use DB;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Exportable;

class UsersExport implements FromCollection, WithHeadings,ShouldQueue
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //return User::select("first_name", "last_name", "email", "join_date", "active", "albums","tracks","language","country","phone_number")->orderBy('id','desc')->get();
        return DB::table('users')->select("first_name", "last_name", "email", "join_date", "active", "albums","tracks","language","country","phone_number")->orderBy('id','desc')->lazy();

    }

    public function headings(): array
    {
        return ["Firstname", "Lastname", "Email", "Joindate", "Active", "Albums","Tracks","Language","Country","Phone Number"];
    }
}
