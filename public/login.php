<?php
//  Ecercice de validation de formulaire d'authetification
// si l'utilisateur est authentifier : 
// - son login et son password sont copiés dans la variable de session
// - il est redirigé vers la page private.php
// - sinon un message est afficher 
// 'Mot de passe ou login invalide'

use Symfony\Component\Yaml\Yaml;
use Twig\Environment;
use Twig\Extension\DebugExtension;
use Twig\Loader\FilesystemLoader;

// activation du système d'autoloading de Composer
require_once __DIR__.'/../vendor/autoload.php';

// instanciation du chargeur de templates
$loader = new FilesystemLoader(__DIR__.'/templates');

// instanciation du moteur de template
$twig = new Environment($loader, [
    // activation du mode debug
    'debug' => true,
    // activation du mode de variables strictes
    'strict_variables' => true,
]);

// chargement de l'extension DebugExtension
$twig->addExtension(new DebugExtension());

// traitement des données
$config = Yaml::parseFile(__DIR__.'/../config/config.yaml');
// $data = [
//     'login' => '',
//     'password' => ''
// ];
// $password = '123';
$errors = [];

if ($_POST) {
    // foreach ($data as $key => $value){
    //     if (isset($_POST[$key])) {
    //         $data[$key] = $_POST[$key];
    //     }
    // }
    // validation du login
    if (empty($_POST['login'])) {
        $errors['login'] = 'Merci de renseigner votre login';
    } else if($_POST['login'] != $config['login']) {
        $errors['login'] = 'Le mot de passe ou le login est invalide.';
        $errors['password'] = 'Le mot de passe ou le login est invalide.';
    }
    // Validation du password
    if (empty($_POST['password'])) {
        $errors['password'] = 'Merci de renseigner votre mot de passe';
    } elseif (!password_verify($_POST['password'], $config['password'])) {
        $errors['login'] = 'Le mot de passe ou le login est invalide.';
        $errors['password'] = 'Le mot de passe ou le login est invalide.';
    }
    
    if (empty($errors)) {
        echo 'Tout est bon';
    }
}

// dump($data);


$config = Yaml::parseFile(__DIR__.'/../config/config.yaml');



// affichage du rendu d'un template
echo $twig->render('login.html.twig', [
    // transmission de données au template
    'errors' => $errors,
    'data' => $data
]);