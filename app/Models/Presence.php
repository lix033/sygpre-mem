<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    //
    protected $fillable = ['employe_id', 'date', 'heure_point','motif', 'presence'];

    public function employe()
{
    return $this->belongsTo(Employes::class);
}

}
