<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Departement;
use Illuminate\Http\Request;

class DepartementController extends Controller
{
    //create departement
    public function create(Request $request){
        $attrs = $request->validate([
            'nom_departement'=>'required',
            'description'=>'required'
        ]);

        $departement = Departement::create($attrs);
    
    }
}
