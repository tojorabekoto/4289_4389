<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\MobileModel;
use App\Models\SelectModel;

class MobileController extends Controller
{
    
    public function index()
    {
        // $db = \Config\Database::connect();

        // $query = $db->query('SELECT * FROM livre ORDER BY id DESC');
        // $produits = $query->getResult();

        return view('Accueil');
    }

    public function client()
    {
        return view('client/page');
    }

    public function add_compte()
    {
        $data = ['numero_telephone' => $this->request->getPost('numero_telephone')];
        $model = new MobileModel();
        $result = $model->add_compte($data);
        $selectModel = new SelectModel();
        $typesOperation = $selectModel->getAllTypesOperation();
        $comptes = $selectModel->getAllComptes();
        if ($result != false) {
            return view('client/home', [
                'data' => $data,
                'types_operation' => $typesOperation,
                'comptes' => $comptes,
                'success' => 'connected and added to db',
                'solde' => 0,
            ]);
        } else {
            $compte = $selectModel->getCompteByNumeroTelephone($data['numero_telephone']);
            $solde = $compte['solde'] ?? 0;
            return view('client/home', [
                'data' => $data,
                'types_operation' => $typesOperation,
                'comptes' => $comptes,
                'error' => 'connected and Account already exists',
                'solde' => $solde,
            ]);
        }
    }

    public function operation()
    {
        $numero_telephone = $this->request->getPost('numero_telephone');
        $epargne =  $this->request->getPost('Epargne');
        $type_operation = $this->request->getPost('type_operation');
        $montant = $this->request->getPost('montant');
        $numero_telephone_destinataire = $this->request->getPost('numero_telephone_destinataire') ?? null;
        $inclure_frais_retrait = $this->request->getPost('inclure_frais_retrait') ?? '0';
        $mobileModel = new MobileModel();
        $data = [
            'numero_telephone' => $numero_telephone,
            'epargne' => $epargne,
            'type_operation' => $type_operation,
            'montant' => $montant,
            'numero_telephone_destinataire' => $numero_telephone_destinataire,
            'inclure_frais_retrait' => $inclure_frais_retrait,
        ];
        $result = $mobileModel->add_operation($data);
        $selectModel = new SelectModel();
        $typesOperation = $selectModel->getAllTypesOperation();
        $comptes = $selectModel->getAllComptes();
        $compte = $selectModel->getCompteByNumeroTelephone($numero_telephone);
        $solde = $compte['solde'] ?? 0;
        if ($result) {
            $successMessage = $type_operation === 'transfert'
                ? 'Vous avez envoyé ' . $montant . ' Ar à ' . ($this->formatDestinatairesForMessage($numero_telephone_destinataire) ?? 'plusieurs destinataires')
                : 'Operation added successfully';

            return view('client/home', [
                'data' => $compte,
                'types_operation' => $typesOperation,
                'comptes' => $comptes,
                'successop' => $successMessage,
                'solde' => $solde,
            ]);
        }

        return view('client/home', [
            'data' => $compte ?? ['numero_telephone' => $numero_telephone],
            'types_operation' => $typesOperation,
            'comptes' => $comptes,
            'errorop' => 'Operation could not be processed',
            'solde' => $solde,
        ]);
    }

    private function formatDestinatairesForMessage($destinataires): ?string
    {
        if (empty($destinataires)) {
            return null;
        }

        if (is_array($destinataires)) {
            return implode(', ', $destinataires);
        }

        return trim((string) $destinataires);
    }

}