<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votre QR Code</title>
</head>
<body>
    <p>Bonjour {{ $employer->prenom }} {{ $employer->nom }},</p>
    <p>Voici votre QR Code unique pour pointer votre présence.</p>
    
    <p>Scannez-le pour vous identifier :</p>
    
    <img src="{{ $qrCodeUrl }}" alt="QR Code" width="200" />

    <p>Vous pouvez aussi télécharger votre QR Code en pièce jointe.</p>

    <p>Merci.</p>

    <p>Cordialement,<br>L'équipe RH</p>
</body>
</html>
