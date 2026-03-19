<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArtistRoleRight extends Model
{
    protected $table="artist_roles_rights";
    use HasFactory;

    protected $fillable = [
        'artist_ownership_identity_id',
        'role',
        'ownership_type',
        'ownership_percentage',
        'co_owners',
    ];

    protected $casts = [
        'co_owners' => 'array',
    ];

    public function artist()
    {
        return $this->belongsTo(ArtistOwnerIdentity::class, 'artist_ownership_identity_id');
    }
}
