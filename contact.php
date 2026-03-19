<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Method not allowed']);
    exit;
}

$prenom    = htmlspecialchars(trim($_POST['prenom'] ?? ''));
$nom       = htmlspecialchars(trim($_POST['nom'] ?? ''));
$email     = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$entreprise = htmlspecialchars(trim($_POST['entreprise'] ?? ''));
$sujet     = htmlspecialchars(trim($_POST['sujet'] ?? ''));
$message   = htmlspecialchars(trim($_POST['message'] ?? ''));

if (empty($prenom) || empty($nom) || empty($email) || empty($message)) {
    echo json_encode(['success' => false, 'error' => 'Champs requis manquants']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'error' => 'Email invalide']);
    exit;
}

$destinataire = 'contact@comcloser.com';
$sujet_mail   = "Nouveau message via comcloser.fr — $sujet";

$corps = "Nouveau message reçu depuis comcloser.fr\n";
$corps .= "==========================================\n\n";
$corps .= "Prénom    : $prenom\n";
$corps .= "Nom       : $nom\n";
$corps .= "Email     : $email\n";
$corps .= "Entreprise: $entreprise\n";
$corps .= "Sujet     : $sujet\n\n";
$corps .= "Message :\n$message\n";

$headers  = "From: noreply@comcloser.fr\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

$envoye = mail($destinataire, $sujet_mail, $corps, $headers);

if ($envoye) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Erreur envoi mail']);
}