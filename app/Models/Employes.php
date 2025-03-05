<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employes extends Model
{
    public function departement(){
        return $this->belongsTo(Departement::class);
    }

    public function presences(){
        return $this->hasMany(Presence::class);
    }
    //
    protected $fillable = ["code_employe","nom","prenom","email","image", "departement_id", "qrcode"];
}
