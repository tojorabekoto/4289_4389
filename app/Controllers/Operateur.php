<?php

namespace App\Controllers;

use App\Models\PrefixeModel;
use App\Models\TypeOperationModel;
use App\Models\TrancheFraisModel;
use App\Models\CompteModel;
use App\Models\TransactionModel;

class Operateur extends BaseController
{
    private const ADMIN_USERNAME = 'admin';
    private const ADMIN_PASSWORD = 'Admin@123';

    private function ensureAdmin()
    {
        $session = service('session');

        if (! $session->get('is_admin')) {
            return redirect()->to('/admin/login');
        }

        return null;
    }

    public function login()
    {
        $session = service('session');

        if ($session->get('is_admin')) {
            return redirect()->to('/operateur/prefixes');
        }

        return view('admin/login');
    }

    public function loginPost()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $session = service('session');

        if ($username === self::ADMIN_USERNAME && $password === self::ADMIN_PASSWORD) {
            $session->set([
                'is_admin'   => true,
                'admin_user' => self::ADMIN_USERNAME,
            ]);

            return redirect()->to('/operateur/prefixes');
        }

        return redirect()->to('/admin/login')
                         ->with('error', 'Identifiants invalides.');
    }

    public function logout()
    {
        $session = service('session');
        $session->remove(['is_admin', 'admin_user']);

        return redirect()->to('/admin/login')->with('success', 'Déconnecté.');
    }

    // ------------------------------------------------------------
    // 1. PREFIXES
    // ------------------------------------------------------------
    public function prefixes()
    {
        if ($redirect = $this->ensureAdmin()) {
            return $redirect;
        }

        $model = new PrefixeModel();

        return view('admin/prefixes', [
            'prefixes' => $model->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function ajouterPrefixe()
    {
        if ($redirect = $this->ensureAdmin()) {
            return $redirect;
        }

        $model = new PrefixeModel();

        $data = [
            'prefixe'     => $this->request->getPost('prefixe'),
            'description' => $this->request->getPost('description'),
            'actif'       => $this->request->getPost('actif') ? 1 : 0,
        ];

        if (! $model->save($data)) {
            return redirect()->to('/operateur/prefixes')
                              ->with('errors', $model->errors());
        }

        return redirect()->to('/operateur/prefixes')
                          ->with('success', 'Préfixe ajouté avec succès.');
    }

    public function basculerPrefixe($id)
    {
        if ($redirect = $this->ensureAdmin()) {
            return $redirect;
        }

        $model   = new PrefixeModel();
        $prefixe = $model->find($id);

        if ($prefixe) {
            $model->update($id, ['actif' => $prefixe['actif'] ? 0 : 1]);
        }

        return redirect()->to('/operateur/prefixes');
    }

    // ------------------------------------------------------------
    // 2. TYPES D'OPERATION + BAREME DE FRAIS
    // ------------------------------------------------------------
    public function operations()
    {
        if ($redirect = $this->ensureAdmin()) {
            return $redirect;
        }

        $tranches = new TrancheFraisModel();
        $types    = new TypeOperationModel();

        return view('admin/operations', [
            'tranches' => $tranches->getTranchesAvecType(),
            'types'    => $types->where('code !=', 'depot')->findAll(),
        ]);
    }

    public function ajouterTranche()
    {
        if ($redirect = $this->ensureAdmin()) {
            return $redirect;
        }

        $model = new TrancheFraisModel();

        $data = [
            'type_operation_id' => $this->request->getPost('type_operation_id'),
            'montant_min'       => $this->request->getPost('montant_min'),
            'montant_max'       => $this->request->getPost('montant_max'),
            'frais'             => $this->request->getPost('frais'),
            'actif'             => 1,
        ];

        if (! $model->save($data)) {
            return redirect()->to('/operateur/operations')
                              ->with('errors', $model->errors());
        }

        return redirect()->to('/operateur/operations')
                          ->with('success', 'Tranche de frais ajoutée avec succès.');
    }

    // ------------------------------------------------------------
    // 3. GAINS
    // ------------------------------------------------------------
    public function gains()
    {
        if ($redirect = $this->ensureAdmin()) {
            return $redirect;
        }

        $transactions = new TransactionModel();

        return view('admin/gains', [
            'gains'     => $transactions->getGains(),
            'gainTotal' => $transactions->getGainTotal(),
        ]);
    }

    // ------------------------------------------------------------
    // 4. COMPTES CLIENTS
    // ------------------------------------------------------------
    public function clients()
    {
        if ($redirect = $this->ensureAdmin()) {
            return $redirect;
        }

        $comptes = new CompteModel();

        return view('admin/clients', [
            'comptes' => $comptes->getSituationComptes(),
        ]);
    }
}
