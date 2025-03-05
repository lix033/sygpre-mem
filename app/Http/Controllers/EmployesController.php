<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employes;
use App\Models\Presence;
use Illuminate\Http\Request;

class EmployesController extends Controller
{

    public function verifyQrCode(Request $request)
{
    try {
        // Valider la requête
        $request->validate([
            'qrCode' => 'required|string',
        ]);

        $qrCode = $request->input('qrCode');
        \Log::info('QR Code reçu : ' . $qrCode);

        // Rechercher l'employé avec le code scanné
        $employe = Employes::where('code_employe', $qrCode)->first();
        \Log::info('Résultat recherche : ' . $employe);

        if (!$employe) {
            \Log::warning('Aucun employé trouvé pour le QR code : ' . $qrCode);
            return response()->json([
                'success' => false,
                'message' => "Aucun employé trouvé pour le QR code scanné.",
            ]);
        }

        // Récupérer l'heure actuelle et la date
        $currentTime = now()->format('H:i');
        $currentDate = now()->toDateString();

        // Vérifier les présences existantes pour la journée actuelle
        $lastPresence = Presence::where('employe_id', $employe->id)
            ->where('date', $currentDate)
            ->latest('heure_point')
            ->first();

        // Déterminer la plage horaire et le motif
        $motif = null;
        $message = null;

        if (!$lastPresence) {
            // Premier pointage de la journée
            if ($currentTime >= '06:00' && $currentTime < '12:00') {
                $motif = 'Arrivée';
                $message = 'Bonjour et bonne journée.';
            } elseif ($currentTime >= '12:00' && $currentTime < '14:00') {
                $motif = 'Pause';
                $message = 'Bon appétit.';
            } elseif ($currentTime >= '14:00' && $currentTime < '17:00') {
                $motif = 'Retour de pause';
                $message = 'Bon retour de votre pause.';
            } elseif ($currentTime >= '18:00' && $currentTime <= '21:00') {
                $motif = 'Fin de journée';
                $message = 'Bon retour chez vous.';
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "L'heure actuelle ne correspond à aucune plage valide.",
                    'data' => [
                        'nom' => $employe->nom,
                        'prenom' => $employe->prenom,
                        'code_employe' => $employe->code_employe,
                        'image' => $employe->image ? asset($employe->image) : null,
                    ],
                ]);
            }
        } else {
            // Cycle Sortie → Retour répété
            switch ($lastPresence->motif) {
                case 'Arrivée':
                    $motif = 'Sortie';
                    $message = 'Sortie enregistrée. À bientôt !';
                    break;

                case 'Sortie':
                    $motif = 'Retour';
                    $message = 'Retour enregistré. Bon courage !';
                    break;

                case 'Retour':
                    $motif = 'Sortie';
                    $message = 'Sortie enregistrée. À bientôt !';
                    break;

                case 'Pause':
                    $motif = 'Retour de pause';
                    $message = 'Bon retour de votre pause.';
                    break;

                case 'Retour de pause':
                    $motif = 'Sortie';
                    $message = 'Sortie enregistrée. À bientôt !';
                    break;

                case 'Fin de journée':
                    return response()->json([
                        'success' => false,
                        'message' => "Votre journée est déjà terminée. Plus de pointage requis.",
                        'data' => [
                            'nom' => $employe->nom,
                            'prenom' => $employe->prenom,
                            'code_employe' => $employe->code_employe,
                            'image' => $employe->image ? asset($employe->image) : null,
                        ],
                    ]);

                default:
                    $message = "Aucune action enregistrée.";
                    break;
            }
        }

        // Enregistrer la présence
        if ($motif) {
            $presence = Presence::create([
                'employe_id' => $employe->id,
                'date' => $currentDate,
                'heure_point' => now()->format('H:i:s'),
                'motif' => $motif,
                'presence' => true,
            ]);
            \Log::info('Présence enregistrée pour l\'employé : ', $presence->toArray());
        }

        // Retourner une réponse avec un message personnalisé
        return response()->json([
            'success' => true,
            'description' => 'Présence enregistrée !',
            'message' => $message,
            'data' => [
                'nom' => $employe->nom,
                'prenom' => $employe->prenom,
                'code_employe' => $employe->code_employe,
                'image' => $employe->image ? asset($employe->image) : null,
            ],
        ]);
    } catch (\Exception $e) {
        \Log::error('Erreur lors de la vérification du QR code : ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Une erreur s\'est produite : ' . $e->getMessage(),
        ], 500);
    }
}


//     public function verifyQrCode(Request $request)
// {
//     try {
//         // Valider la requête
//         $request->validate(['qrCode' => 'required|string']);
//         $qrCode = $request->input('qrCode');
//         \Log::info('QR Code reçu : ' . $qrCode);

//         // Rechercher l'employé
//         $employe = Employes::where('code_employe', $qrCode)->first();
//         \Log::info('Résultat recherche : ' . ($employe ? $employe->toJson() : 'Aucun employé trouvé'));

//         if (!$employe) {
//             return response()->json([
//                 'success' => false,
//                 'message' => "Aucun employé trouvé pour le QR code scanné.",
//             ]);
//         }

//         // Récupérer l'heure actuelle et la date
//         $currentTime = now()->format('H:i');
//         $currentDate = now()->toDateString();

//         // Récupérer la dernière présence de l'employé pour la journée
//         $lastPresence = Presence::where('employe_id', $employe->id)
//             ->where('date', $currentDate)
//             ->latest('heure_point')
//             ->first();

//         $motif = null;
//         $message = null;

//         if (!$lastPresence) {
//             // Premier pointage de la journée
//             if ($currentTime >= '06:00' && $currentTime < '12:00') {
//                 $motif = 'Arrivée';
//                 $message = 'Bonjour et bonne journée.';
//             } elseif ($currentTime >= '12:00' && $currentTime < '14:00') {
//                 $motif = 'Pause';
//                 $message = 'Bon appétit.';
//             } elseif ($currentTime >= '14:00' && $currentTime < '17:00') {
//                 $motif = 'Retour de pause';
//                 $message = 'Bon retour de votre pause.';
//             } elseif ($currentTime >= '18:00' && $currentTime <= '21:00') {
//                 $motif = 'Fin de journée';
//                 $message = 'Bon retour chez vous.';
//             } else {
//                 $message = "L'heure actuelle ne correspond à aucune plage valide.";
//             }
//         } else {
//             // Gestion des actions consécutives
//             switch ($lastPresence->motif) {
//                 case 'Arrivée':
//                     if ($currentTime >= '06:00' && $currentTime < '12:00') {
//                         $motif = 'Sortie';
//                         $message = 'Sortie enregistrée. À bientôt !';
//                     }
//                     break;

//                 case 'Sortie':
//                     if ($currentTime >= '06:00' && $currentTime < '12:00') {
//                         $motif = 'Retour';
//                         $message = 'Retour enregistré. Bon courage !';
//                     }
//                     break;

//                 case 'Retour':
//                     if ($currentTime >= '06:00' && $currentTime < '12:00') {
//                         $motif = 'Sortie';
//                         $message = 'Sortie enregistrée. À bientôt !';
//                     }
//                     break;

//                 case 'Pause':
//                     if ($currentTime >= '12:00' && $currentTime < '14:00') {
//                         $motif = 'Retour de pause';
//                         $message = 'Bon retour de votre pause.';
//                     }
//                     break;

//                 case 'Retour de pause':
//                     if ($currentTime >= '12:00' && $currentTime < '14:00') {
//                         $motif = 'Sortie';
//                         $message = 'Sortie enregistrée. À bientôt !';
//                     }
//                     break;

//                 case 'Fin de journée':
//                     $message = "Votre journée est déjà terminée. Plus de pointage requis.";
//                     break;

//                 default:
//                     $message = "Action non reconnue.";
//                     break;
//             }
//         }

//         // Enregistrer uniquement si un motif est défini
//         if ($motif) {
//             $presence = Presence::create([
//                 'employe_id' => $employe->id,
//                 'date' => $currentDate,
//                 'heure_point' => now()->format('H:i:s'),
//                 'motif' => $motif,
//                 'presence' => true,
//             ]);
//             \Log::info('Présence enregistrée : ' . $presence->toJson());
//         }

//         // Retourner une réponse, même sans enregistrement
//         return response()->json([
//             'success' => true,
//             'message' => $message ?? "Aucune action enregistrée.",
//             'data' => [
//                 'nom' => $employe->nom,
//                 'prenom' => $employe->prenom,
//                 'code_employe' => $employe->code_employe,
//                 'image' => $employe->image ? asset($employe->image) : null,
//             ],
//         ]);
//     } catch (\Exception $e) {
//         \Log::error('Erreur : ' . $e->getMessage());
//         return response()->json([
//             'success' => false,
//             'message' => 'Une erreur s\'est produite : ' . $e->getMessage(),
//         ], 500);
//     }
// }


//     public function verifyQrCode(Request $request)
// {
//     try {
//         $request->validate(['qrCode' => 'required|string']);
//         $qrCode = $request->input('qrCode');
//         \Log::info('QR Code reçu : ' . $qrCode);

//         // Rechercher l'employé avec le code scanné
//         $employe = Employes::where('code_employe', $qrCode)->first();
//         \Log::info('Résultat recherche : ' . ($employe ? $employe->toJson() : 'Aucun employé trouvé'));

//         if (!$employe) {
//             return response()->json([
//                 'success' => false,
//                 'message' => "Aucun employé trouvé pour le QR code scanné.",
//             ]);
//         }

//         $currentTime = now()->format('H:i');
//         $currentDate = now()->toDateString();

//         // Récupérer la dernière présence
//         $lastPresence = Presence::where('employe_id', $employe->id)
//             ->where('date', $currentDate)
//             ->latest('heure_point')
//             ->first();

//         $motif = null;
//         $message = null;

//         if (!$lastPresence) {
//             // Premier pointage de la journée
//             if ($currentTime >= '06:00' && $currentTime < '12:00') {
//                 $motif = 'Arrivée';
//                 $message = 'Bonjour et bonne journée.';
//             } elseif ($currentTime >= '12:00' && $currentTime < '14:00') {
//                 $motif = 'Pause';
//                 $message = 'Bon appétit.';
//             } elseif ($currentTime >= '14:00' && $currentTime < '17:00') {
//                 $motif = 'Retour de pause';
//                 $message = 'Bon retour de votre pause.';
//             } elseif ($currentTime >= '18:00' && $currentTime <= '21:00') {
//                 $motif = 'Fin de journée';
//                 $message = 'Bon retour chez vous.';
//             } else {
//                 $message = "L'heure actuelle ne correspond à aucune plage valide.";
//             }
//         } else {
//             // Vérifier l'état précédent
//             switch ($lastPresence->motif) {
//                 case 'Arrivée':
//                     if ($currentTime >= '06:00' && $currentTime < '12:00') {
//                         $motif = 'Sortie';
//                         $message = 'Sortie enregistrée. À bientôt !';
//                     }
//                     break;

//                 case 'Sortie':
//                     if ($currentTime >= '06:00' && $currentTime < '12:00') {
//                         $motif = 'Retour';
//                         $message = 'Retour enregistré. Bon courage !';
//                     }
//                     break;

//                 case 'Pause':
//                     if ($currentTime >= '12:00' && $currentTime < '14:00') {
//                         $motif = 'Retour de pause';
//                         $message = 'Bon retour de votre pause.';
//                     }
//                     break;

//                 case 'Retour de pause':
//                     if ($currentTime >= '12:00' && $currentTime < '14:00') {
//                         $motif = 'Sortie';
//                         $message = 'Sortie enregistrée. À bientôt !';
//                     }
//                     break;

//                 case 'Fin de journée':
//                     $message = "Votre journée est déjà terminée. Plus de pointage requis.";
//                     break;
//             }
//         }

//         // Enregistrer la présence uniquement si un motif est défini
//         if ($motif) {
//             $presence = Presence::create([
//                 'employe_id' => $employe->id,
//                 'date' => $currentDate,
//                 'heure_point' => now()->format('H:i:s'),
//                 'motif' => $motif,
//                 'presence' => true,
//             ]);
//             \Log::info('Présence enregistrée : ' . $presence->toJson());
//         }

//         // Toujours retourner les informations de l'employé
//         return response()->json([
//             'success' => true,
//             'message' => $message ?? "Aucune action enregistrée.",
//             'data' => [
//                 'nom' => $employe->nom,
//                 'prenom' => $employe->prenom,
//                 'code_employe' => $employe->code_employe,
//                 'image' => $employe->image ? asset($employe->image) : null,
//             ],
//         ]);
//     } catch (\Exception $e) {
//         \Log::error('Erreur : ' . $e->getMessage());
//         return response()->json([
//             'success' => false,
//             'message' => 'Une erreur s\'est produite : ' . $e->getMessage(),
//         ], 500);
//     }
// }



//     public function verifyQrCode(Request $request)
// {
//     try {
//         // Valider la requête
//         $request->validate([
//             'qrCode' => 'required|string',
//         ]);

//         $qrCode = $request->input('qrCode');
//         \Log::info('QR Code reçu : ' . $qrCode);
        
//         // Rechercher l'employé avec le code scanné
//         $employe = Employes::where('code_employe', $qrCode)->first();
//         \Log::info('Resultat recherche : ' . $employe);

//         if (!$employe) {
//             \Log::warning('Aucun employé trouvé pour le QR code : ' . $qrCode);
//             return response()->json([
//                 'success' => false,
//                 'message' => "Aucun employé trouvé pour le QR code scanné.",
//             ]);
//         }

//         // Récupérer l'heure actuelle et la date
//         $currentTime = now()->format('H:i');
//         $currentDate = now()->toDateString();

//         // Vérifier les présences existantes pour la journée actuelle
//         $lastPresence = Presence::where('employe_id', $employe->id)
//             ->where('date', $currentDate)
//             ->latest('heure_point')
//             ->first();

//         // Déterminer la plage horaire et le motif
//         $motif = null;
//         $message = null;

//         if (!$lastPresence) {
//             if ($currentTime >= '06:00' && $currentTime < '12:00') {
//                 $motif = 'Arrivée';
//                 $message = 'Bonjour et bonne journée.';
//             } elseif ($currentTime >= '12:00' && $currentTime < '14:00') {
//                 $motif = 'Pause';
//                 $message = 'Bon appétit.';
//             } elseif ($currentTime >= '14:00' && $currentTime < '17:00') {
//                 $motif = 'Retour de pause';
//                 $message = 'Bon retour de votre pause.';
//             } elseif ($currentTime >= '18:00' && $currentTime <= '21:00') {
//                 $motif = 'Fin de journée';
//                 $message = 'Bon retour chez vous.';
//             } else {
//                 return response()->json([
//                     'success' => false,
//                     'message' => "L'heure actuelle ne correspond à aucune plage valide.",
//                     'data' => [
//                         'nom' => $employe->nom,
//                         'prenom' => $employe->prenom,
//                         'code_employe' => $employe->code_employe,
//                         'image' => $employe->image ? asset($employe->image) : null,
//                     ],
//                 ]);
//             }
//         } else {
//             switch ($lastPresence->motif) {
//                 case 'Arrivée':
//                     $motif = 'Sortie';
//                     $message = 'Sortie enregistrée. À bientôt !';
//                     break;

//                 case 'Sortie':
//                     $motif = 'Retour';
//                     $message = 'Retour enregistré. Bon courage !';
//                     break;

//                 case 'Pause':
//                     $motif = 'Retour de pause';
//                     $message = 'Bon retour de votre pause.';
//                     break;

//                 case 'Retour de pause':
//                     $motif = 'Sortie';
//                     $message = 'Sortie enregistrée. À bientôt !';
//                     break;

//                 case 'Fin de journée':
//                     return response()->json([
//                         'success' => false,
//                         'message' => "Votre journée est déjà terminée. Plus de pointage requis.",
//                         'data' => [
//                             'nom' => $employe->nom,
//                             'prenom' => $employe->prenom,
//                             'code_employe' => $employe->code_employe,
//                             'image' => $employe->image ? asset($employe->image) : null,
//                         ],
//                     ]);
//             }
//         }

//         // Enregistrer la présence
//         $presence = Presence::create([
//             'employe_id' => $employe->id,
//             'date' => $currentDate,
//             'heure_point' => now()->format('H:i:s'),
//             'motif' => $motif,
//             'presence' => true,
//         ]);

//         \Log::info('Présence enregistrée pour l\'employé : ', $presence->toArray());

//         // Retourner une réponse avec un message personnalisé
//         return response()->json([
//             'success' => true,
//             'description' => 'Présence enregistrée !',
//             'message' => $message,
//             'data' => [
//                 'nom' => $employe->nom,
//                 'prenom' => $employe->prenom,
//                 'code_employe' => $employe->code_employe,
//                 'image' => $employe->image ? asset($employe->image) : null, // Vérification image
//             ],
//         ]);
//     } catch (\Exception $e) {
//         \Log::error('Erreur lors de la vérification du QR code : ' . $e->getMessage());
//         return response()->json([
//             'success' => false,
//             'message' => 'Une erreur s\'est produite : ' . $e->getMessage(),
//         ], 500);
//     }
// }


}