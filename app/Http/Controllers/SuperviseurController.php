<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\QrCodeMail;
use App\Models\Departement;
use App\Models\Employes;
use App\Models\Presence;
use App\Models\Superviseur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class SuperviseurController extends Controller
{
    public function accceuil() {}

    public function loginvue()
    {
        return view('adminpages.login');
    }

    public function ajoutEmployeVue()
    {
        $departements = Departement::orderBy('nom_departement', 'ASC')->get();
        return view('adminpages.ajouter-employe', compact('departements'));
    }

    public function listeEmployeVue()
    {
        $listEmployes = Employes::orderBy('nom', 'ASC')->get();
        return view('adminpages.liste-employes', compact('listEmployes'));
    }
    public function ajoutDepartementVue()
    {
        return view('adminpages.ajouter-departement');
    }

    public function listeDepartementVue()
    {
        $listDepartements = Departement::orderBy('nom_departement', 'ASC')->get();
        return view('adminpages.liste-departement', compact('listDepartements'));
    }

    public function dayPresentVue()
    {
        $currentDate = now()->toDateString();
        $presenceJour = Presence::where('date', $currentDate)->get();
        \Log::info('Resutlats request : ' . $presenceJour);
        return view('adminpages.day-present', compact('presenceJour'));
    }
    public function AbsentVue()
    {
        $currentDate = now()->toDateString();
        $employesPresents = Presence::where('date', $currentDate)->pluck('employe_id');
        // Récupérer les employés absents
        $employesAbsents = Employes::whereNotIn('id', $employesPresents)->get();
        \Log::info('Resutlats request : ' . $employesAbsents);
        return view('adminpages.absent', compact('employesAbsents'));
    }

    public function loginRequest(Request $request)
    {
        $attrs = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        // Tenter de connecter en utilisant le pseudo
        // $credentials = ['pseudo' => $request->pseudo, 'password' => $request->password];

        if (!Auth::guard('superviseur')->attempt([
            'email' => $attrs['email'],
            'password' => $attrs['password']
        ])) {
            return back()->withErrors('Les identifiants ne sont pas corrects');
        }
        // $user = auth()->user();
        return redirect()->route('route.dash.page');
    }

    //list of employees
    public function index()
    {
        $totalEmployes = Employes::count();
        $totalSuperviseurs = Superviseur::count();
        $presenceJour = Presence::where('date', now()->toDateString())
            ->where('motif', "Arrivée")
            ->count();
        return view('adminpages.index', compact('totalEmployes', 'totalSuperviseurs', 'presenceJour'));
    }

    //create employer
    public function createEmployer(Request $request)
    {

        $attrs = $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'email' => 'required',
            'image' => 'required',
            'departement' => 'required',
        ]);

        // Vérifier si l'employé existe déjà avec cet email
        $existingEmployee = Employes::where('email', $attrs['email'])->first();

        if ($existingEmployee) {
            return redirect()->back()->withErrors('Un employé avec cet email existe déjà.');
        }

        $code_employe = "TG" . mt_rand(10000, 99999) . date('Y');

        // Définir le chemin correct du QR Code dans storage/app/public/qrcodes/
        $qrCodeFileName = 'qr-' . $code_employe . '.svg';
        $qrCodeStoragePath = 'public/qrcodes/' . $qrCodeFileName; 
        $qrCodePublicPath = 'storage/qrcodes/' . $qrCodeFileName; 

        // Générer et sauvegarder le QR Code
        $qrcode = QrCode::format('svg')->size(586)->generate($code_employe);
        Storage::put($qrCodeStoragePath, $qrcode);

        $image = base64_encode(file_get_contents($attrs['image']));
        $saveEmpImage = $this->saveImage($image, 'employes');

        // Save employee to the database
        $employer = Employes::create([
            'code_employe' => $code_employe,
            'nom' => $attrs['nom'],
            'prenom' => $attrs['prenom'],
            'email' => $attrs['email'],
            'image' => $saveEmpImage,
            'departement_id' => $attrs['departement'],
            'qrcode' => $qrCodePublicPath
        ]);

        // URL publique du QR Code
        $qrCodeUrl = url($qrCodePublicPath);

        // Envoyer l'email avec le QR Code attaché
        Mail::to($employer->email)->send(new QrCodeMail($employer, Storage::path($qrCodeStoragePath), $qrCodeUrl));



        return redirect()->route('route.ajout.employe')->with('success', 'Nouveau employe ajouter.');
    }

    //update employe function
    public function updateEmploye(Request $request, $id)
    {
        $attrs = $request->validate([
            'nom' => 'required',
            'prenom' => 'required',
            'image' => 'required',
            'departement' => 'required',
        ]);

        // Find the employee
        $employe = Employes::find($id);
        //update the employee
        if ($employe) {
            $employe->update([
                'nom' => $attrs['nom'],
                'prenom' => $attrs['prenom'],
                'image' => $attrs['image'],
                'departement' => $attrs['departement'],
            ]);
        }
    }

    public function createDepartement(Request $request)
    {
        $attrs = $request->validate([
            'nom_departement' => 'required',
            'description' => 'required'
        ]);

        $departement = Departement::create($attrs);
        return redirect()->route('route.ajout.departement')->with('success', 'Nouveau département ajouter.');
    }

    public function logout()
    {
        auth()->guard('superviseur')->logout();
        return redirect()->route('route.login.page');
    }
}
