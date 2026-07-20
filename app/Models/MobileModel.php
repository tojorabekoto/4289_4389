<?php

namespace App\Models;

use App\Models\PrefixeModel;
use App\Models\TrancheFraisModel;
use App\Models\TypeOperationModel;
use CodeIgniter\Model;

class MobileModel extends Model
{
    public function add_compte($data)
    {
        $db = \Config\Database::connect();
        if(!$this->exists($data['numero_telephone'])) {
            $builder = $db->table('comptes');
            return $builder->insert($data);
        } else {
            // Handle the case where the account already exists
            return false; // or throw an exception, or return a specific message
        }
    }

    //     $builder = $db->table('comptes');
    //     return $builder->insert($data);
    // }

    public function add_operation($data)
    {
        $db = \Config\Database::connect();

        $numeroTelephone = $data['numero_telephone'];
        $typeOperation = $data['type_operation'];
        $montant = (float) $data['montant'];
        $numeroTelephoneDestinataire = $data['numero_telephone_destinataire'] ?? null;

        $typeOperationModel = new TypeOperationModel();
        $trancheFraisModel = new TrancheFraisModel();
        $prefixeModel = new PrefixeModel();

        $typeOperationData = $typeOperationModel->getParCode($typeOperation);
        if (! $typeOperationData) {
            return false;
        }

        $fraisConfig = ['frais' => 0.0, 'pourcentage_autre_operateur' => 0.0];
        if (in_array($typeOperation, ['retrait', 'transfert'], true)) {
            $fraisConfig = $trancheFraisModel->getFraisApplicable((int) $typeOperationData['id'], $montant);
        }

        $db->transStart();

        $sourceCompte = $db->table('comptes')
            ->where('numero_telephone', $numeroTelephone)
            ->get()
            ->getRowArray();

        if (!$sourceCompte) {
            $db->transRollback();
            return false;
        }

        $newSourceSolde = (float) $sourceCompte['solde'];
        $totalFrais = (float) $fraisConfig['frais'];
        $transferType = null;
        $destinationCompte = null;

        if ($typeOperation === 'transfert'
            && ! empty($numeroTelephoneDestinataire)
            && $prefixeModel->estUnAutreOperateur($numeroTelephone, $numeroTelephoneDestinataire)
        ) {
            $totalFrais += $montant * ((float) $fraisConfig['pourcentage_autre_operateur'] / 100.0);
        }

        if ($typeOperation === 'depot') {
            $newSourceSolde += $montant;
            $db->table('comptes')
                ->where('numero_telephone', $numeroTelephone)
                ->update(['solde' => $newSourceSolde]);
        } elseif ($typeOperation === 'retrait') {
            if ($newSourceSolde < $montant + $totalFrais) {
                $db->transRollback();
                return false;
            }

            $newSourceSolde -= $montant + $totalFrais;
            $db->table('comptes')
                ->where('numero_telephone', $numeroTelephone)
                ->update(['solde' => $newSourceSolde]);
        } elseif ($typeOperation === 'transfert') {
            if ($newSourceSolde < $montant + $totalFrais || empty($numeroTelephoneDestinataire)) {
                $db->transRollback();
                return false;
            }

            $destinationCompte = $db->table('comptes')
                ->where('numero_telephone', $numeroTelephoneDestinataire)
                ->get()
                ->getRowArray();

            if (!$destinationCompte) {
                $db->transRollback();
                return false;
            }

            $transferType = $prefixeModel->estUnAutreOperateur($numeroTelephone, $numeroTelephoneDestinataire)
                ? 'externe'
                : 'interne';

            $newDestinationSolde = (float) $destinationCompte['solde'] + $montant;
            $newSourceSolde -= $montant + $totalFrais;

            $db->table('comptes')
                ->where('numero_telephone', $numeroTelephone)
                ->update(['solde' => $newSourceSolde]);

            $db->table('comptes')
                ->where('numero_telephone', $numeroTelephoneDestinataire)
                ->update(['solde' => $newDestinationSolde]);
        } else {
            $db->transRollback();
            return false;
        }

        $transactionData = [
            'compte_id' => $sourceCompte['id'],
            'compte_destinataire_id' => $destinationCompte['id'] ?? null,
            'type_operation_id' => (int) $typeOperationData['id'],
            'montant' => $montant,
            'frais' => $totalFrais,
            'solde_apres' => $newSourceSolde,
        ];

        if ($transferType !== null) {
            $transactionData['transfert_type'] = $transferType;
        }

        $db->table('transactions')->insert($transactionData);

        $db->transComplete();

        return $db->transStatus();
    }

    public function exists($numero_telephone)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('comptes');
        $builder->where('numero_telephone', $numero_telephone);
        return $builder->countAllResults() > 0;
    }


}
?>