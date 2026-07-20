<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Auth extends BaseController
{
    // Identifiants admin en dur (sans modifier la DB)
    private $ADMIN_USERNAME = 'admin';
    private $ADMIN_PASSWORD = 'admin123';

    public function login()
    {
        // Si déjà connecté, rediriger vers le tableau de bord
        if (session()->get('admin_logged_in')) {
            return redirect()->to('/operateur/prefixes');
        }

        if ($this->request->getMethod() === 'post') {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            // Vérification des identifiants
            if ($username === $this->ADMIN_USERNAME && $password === $this->ADMIN_PASSWORD) {
                // Stocker la session admin
                session()->set('admin_logged_in', true);
                session()->set('admin_username', $username);

                return redirect()->to('/operateur/prefixes')->with('success', 'Connexion réussie!');
            } else {
                return redirect()->to('/admin/login')->with('error', 'Identifiant ou mot de passe incorrect.');
            }
        }

        return view('admin/login');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/admin/login')->with('success', 'Déconnexion réussie.');
    }
}
