<?php

namespace App\Models;

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

        if ($typeOperation === 'depot') {
            $newSourceSolde += $montant;
            $db->table('comptes')
                ->where('numero_telephone', $numeroTelephone)
                ->update(['solde' => $newSourceSolde]);
        } elseif ($typeOperation === 'retrait') {
            if ($newSourceSolde < $montant) {
                $db->transRollback();
                return false;
            }

            $newSourceSolde -= $montant;
            $db->table('comptes')
                ->where('numero_telephone', $numeroTelephone)
                ->update(['solde' => $newSourceSolde]);
        } elseif ($typeOperation === 'transfert') {
            if ($newSourceSolde < $montant || empty($numeroTelephoneDestinataire)) {
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

            $newDestinationSolde = (float) $destinationCompte['solde'] + $montant;
            $newSourceSolde -= $montant;

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