<?php

namespace App\Controllers;

use App\Models\PrefixeModel;
use App\Models\TypeOperationModel;
use App\Models\TrancheFraisModel;
use App\Models\CompteModel;
use App\Models\TransactionModel;

class Operateur extends BaseController
{
    protected function checkAuth()
    {
        if (! session()->get('admin_logged_in')) {
            return redirect()->to('/admin/login');
        }
        return true;
    }

    // ------------------------------------------------------------
    // 1. PREFIXES
    // ------------------------------------------------------------
    public function prefixes()
    {
        if ($this->checkAuth() !== true) {
            return $this->checkAuth();
        }

        $model = new PrefixeModel();

        return view('admin/prefixes', [
            'prefixes' => $model->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function ajouterPrefixe()
    {
        if ($this->checkAuth() !== true) {
            return $this->checkAuth();
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
        if ($this->checkAuth() !== true) {
            return $this->checkAuth();
        }

        $model   = new PrefixeModel();
        $prefixe = $model->find($id);

        if ($prefixe) {
            $model->update($id, ['actif' => $prefixe['actif'] ? 0 : 1]);
        }

        return redirect()->to('/operateur/prefixes');
    }

    // ------------------------------------------------------------
    // 2if ($this->checkAuth() !== true) {
            return $this->checkAuth();
        }

        . TYPES D'OPERATION + BAREME DE FRAIS
    // ------------------------------------------------------------
    public function operations()
    {
        $tranches = new TrancheFraisModel();
        $types    = new TypeOperationModel();

        return view('admin/operations', [
            'tranches' => $tranches->getTranchesAvecType(),
            'types'    => $types->where('code !=', 'depot')->findAll(),
        ]);
    }if ($this->checkAuth() !== true) {
            return $this->checkAuth();
        }

        

    public function ajouterTranche()
    {
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
        if ($this->checkAuth() !== true) {
            return $this->checkAuth();
        }

                          ->with('success', 'Tranche de frais ajoutée avec succès.');
    }

    // ------------------------------------------------------------
    // 3. GAINS
    // ------------------------------------------------------------
    public function gains()
    {
        $transactions = new TransactionModel();

        return view('admin/gains', [
            'gains'     => $transactions->getGains(),
            'gainTotal' => $transactions->getGainTotal(),
        if ($this->checkAuth() !== true) {
            return $this->checkAuth();
        }

        ]);
    }

    // ------------------------------------------------------------
    // 4. COMPTES CLIENTS
    // ------------------------------------------------------------
    public function clients()
    {
        $comptes = new CompteModel();

        return view('admin/clients', [
            'comptes' => $comptes->getSituationComptes(),
        ]);
    }
}
