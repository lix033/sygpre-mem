<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
class Superviseur extends Model implements AuthenticatableContract
{
    //
    use HasFactory;
    use Authenticatable;

    protected $fillable = ['nom_sup', 'email', 'password'];
}
