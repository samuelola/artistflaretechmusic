<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait FilterByTenant{

     public static function boot(){

        parent::boot();

        // self::creating(function($model){
        //      $model->user_id = auth()->id();
        // });
        $currentTenantID = auth()->user()->tenants()->first()->id;
        self::creating(function($model) use ($currentTenantID) {
            $model->tenant_id = $currentTenantID;
        });

        self::addGlobalScope(function (Builder $builder) use ($currentTenantID){
            $builder->where('tenant_id',$currentTenantID);
        });
     }
}