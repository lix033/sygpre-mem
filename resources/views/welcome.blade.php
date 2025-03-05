<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>QR SCAN</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
        <link rel="stylesheet" href="{{asset('style.css')}}">
    <script src="{{asset('html5-qrcode.min.js')}}"></script>
  
</head>

<body>
    <div class="row">
        <div class="scanner-container">
            <div id="reader"></div>
        </div>

        <div class="result-wrapper">
            <div id="result" class="result-container">
                <div class="photo" id="photo-container">
                    <span>No photo</span>
                </div>
                <div class="info">
                    <p id="name">Nom : -</p>
                    <p id="prenom">Prénom : -</p>
                    <p id="matricule">Matricule : -</p>
                </div>
                <p id="alert" class="alert alert-warning"></p>
            </div>
        </div>
    </div>

    <script>
        let isScanning = true;
       
        function onScanSuccess(qrCodeMessage) {
    if (!isScanning) {
        return;
    }

    isScanning = false;

    fetch('/verify-qr', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ qrCode: qrCodeMessage }),
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then((data) => {
            const employe = data.data || {};

            // Afficher les informations de l'employé
            if (employe.nom) {
                document.getElementById('name').innerText = `Nom : ${employe.nom}`;
                document.getElementById('prenom').innerText = `Prénom : ${employe.prenom}`;
                document.getElementById('matricule').innerText = `Matricule : ${employe.code_employe}`;
                const photoContainer = document.getElementById('photo-container');
                photoContainer.innerHTML = `<img src="${employe.image}" alt="Photo">`;
            } else {
                document.getElementById('name').innerText = "Aucun employé trouvé.";
                document.getElementById('prenom').innerText = "";
                document.getElementById('matricule').innerText = "";
                document.getElementById('photo-container').innerHTML = `<span>No photo</span>`;
            }

            // Afficher le message
            const resultContainer = document.getElementById('result');
            document.querySelectorAll('.alert').forEach((alert) => alert.remove());
            resultContainer.insertAdjacentHTML(
                'beforeend',
                `<div class="alert ${data.success ? 'alert-success' : 'alert-warning'}" role="alert">
                    ${data.message}
                </div>`
            );
        })
        .catch((error) => {
            console.error('Erreur de fetch :', error);
            document.getElementById('name').innerText = "Erreur";
            document.getElementById('prenom').innerText = "";
            document.getElementById('matricule').innerText = "";
            document.getElementById('photo-container').innerHTML = `<span>No photo</span>`;
        })
        .finally(() => {
            setTimeout(() => {
                isScanning = true;
            }, 2000); // Réinitialiser pour un nouveau scan
        });
}


        // function onScanSuccess(qrCodeMessage) {
        //     if (!isScanning) {
        //         return;
        //     }
        
        //     isScanning = false;
        
        //     fetch('/verify-qr', {
        //         method: 'POST',
        //         headers: {
        //             'Content-Type': 'application/json',
        //             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        //         },
        //         body: JSON.stringify({ qrCode: qrCodeMessage }),
        //     })
        //         .then((response) => {
        //             if (!response.ok) {
        //                 throw new Error(`HTTP error! Status: ${response.status}`);
        //             }
        //             return response.json();
        //         })
        //         .then((data) => {
        //             const employe = data.data || {};
        
        //             // Afficher les informations de l'employé
        //             if (employe.nom) {
        //                 document.getElementById('name').innerText = `Nom : ${employe.nom}`;
        //                 document.getElementById('prenom').innerText = `Prénom : ${employe.prenom}`;
        //                 document.getElementById('matricule').innerText = `Matricule : ${employe.code_employe}`;
        //                 const photoContainer = document.getElementById('photo-container');
        //                 photoContainer.innerHTML = `<img src="${employe.image}" alt="Photo">`;
        //             } else {
        //                 document.getElementById('name').innerText = "Aucun employé trouvé.";
        //                 document.getElementById('prenom').innerText = "";
        //                 document.getElementById('matricule').innerText = "";
        //                 document.getElementById('photo-container').innerHTML = `<span>No photo</span>`;
        //             }
        
        //             // Afficher le message
        //             const resultContainer = document.getElementById('result');
        //             document.querySelectorAll('.alert').forEach((alert) => alert.remove());
        //             resultContainer.insertAdjacentHTML(
        //                 'beforeend',
        //                 `<div class="alert ${data.success ? 'alert-success' : 'alert-warning'}" role="alert">
        //                     ${data.message}
        //                 </div>`
        //             );
        //         })
        //         .catch((error) => {
        //             console.error('Erreur de fetch :', error);
        //             document.getElementById('name').innerText = "Erreur";
        //             document.getElementById('prenom').innerText = "";
        //             document.getElementById('matricule').innerText = "";
        //             document.getElementById('photo-container').innerHTML = `<span>No photo</span>`;
        //         })
        //         .finally(() => {
        //             setTimeout(() => {
        //                 isScanning = true;
        //             }, 2000);
        //         });
        // }
        
        function onScanError(errorMessage) {
            console.error("Scan error:", errorMessage);
        }
        
        var html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 310 });
        html5QrcodeScanner.render(onScanSuccess, onScanError);
        </script>
        
  
    {{-- <script>
        let isScanning = true; // Contrôle du scanner
function onScanSuccess(qrCodeMessage) {
    if (!isScanning) {
        return;
    }

    isScanning = false;

    fetch('/verify-qr', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ qrCode: qrCodeMessage })
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Réponse JSON complète :', data);

        const employe = data.data || {}; // Empêche les erreurs si `data.data` est null

        if (employe.nom) {
            // Afficher les informations de l'employé
            document.getElementById('name').innerText = `Nom : ${employe.nom}`;
            document.getElementById('prenom').innerText = `Prénom : ${employe.prenom}`;
            document.getElementById('matricule').innerText = `Matricule : ${employe.code_employe}`;
            const photoContainer = document.getElementById('photo-container');
            photoContainer.innerHTML = `<img src="${employe.image}" alt="Photo">`;
        } else {
            document.getElementById('name').innerText = "Aucun employé trouvé.";
            document.getElementById('prenom').innerText = "";
            document.getElementById('matricule').innerText = "";
            document.getElementById('photo-container').innerHTML = `<span>No photo</span>`;
        }

        // Afficher le message
        const resultContainer = document.getElementById('result');
        document.querySelectorAll('.alert').forEach(alert => alert.remove());
        resultContainer.insertAdjacentHTML(
            'beforeend',
            `<div class="alert ${data.success ? 'alert-success' : 'alert-danger'}" role="alert">
                ${data.message}
            </div>`
        );
    })
    .catch(error => {
        console.error('Erreur de fetch :', error);
        document.getElementById('name').innerText = "Erreur";
        document.getElementById('prenom').innerText = "";
        document.getElementById('matricule').innerText = "";
        document.getElementById('photo-container').innerHTML = `<span>No photo</span>`;
    })
    .finally(() => {
        // Réinitialiser pour permettre un nouveau scan immédiatement
        isScanning = true;
    });
}



        function onScanError(errorMessage) {
            // console.error("Scan error:", errorMessage);
        }

        var html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", { fps: 10, qrbox: 310 });
        html5QrcodeScanner.render(onScanSuccess, onScanError);
        
    </script> --}}

</body>

</html>

