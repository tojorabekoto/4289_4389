<?php

namespace App\Models;

use CodeIgniter\Model;

class MobileModel extends Model
{
    public function add_compte($data)
    {
        $db = \Config\Database::connect();
        if (!$this->exists($data['numero_telephone'])) {
            $builder = $db->table('comptes');
            return $builder->insert($data);
        }

        return false;
    }

    public function add_operation($data)
    {
        $db = \Config\Database::connect();

        $numeroTelephone = $data['numero_telephone'];
        $typeOperation = $data['type_operation'];
        $montant = (float) $data['montant'];
        $numeroTelephoneDestinataire = $data['numero_telephone_destinataire'] ?? null;
        $inclureFraisRetrait = isset($data['inclure_frais_retrait']) && filter_var($data['inclure_frais_retrait'], FILTER_VALIDATE_BOOLEAN);

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
            $frais = $this->getFraisApplicable('retrait', $montant);
            $totalDebit = $inclureFraisRetrait ? $montant + $frais : $montant;

            if ($newSourceSolde < $totalDebit) {
                $db->transRollback();
                return false;
            }

            $newSourceSolde -= $totalDebit;
            $db->table('comptes')
                ->where('numero_telephone', $numeroTelephone)
                ->update(['solde' => $newSourceSolde]);
        } elseif ($typeOperation === 'transfert') {
            $destinataires = $this->parseDestinataires($numeroTelephoneDestinataire);

            if ($destinataires === [] || $montant <= 0) {
                $db->transRollback();
                return false;
            }

            $frais = $this->getFraisApplicable('transfert', $montant);
            $totalDebit = $inclureFraisRetrait ? $montant + $frais : $montant;

            if ($newSourceSolde < $totalDebit) {
                $db->transRollback();
                return false;
            }

            $plan = $this->buildTransferPlan($montant, $numeroTelephoneDestinataire, $inclureFraisRetrait, $frais);

            foreach ($plan as $recipient) {
                $destinationCompte = $db->table('comptes')
                    ->where('numero_telephone', $recipient['numero_telephone'])
                    ->get()
                    ->getRowArray();

                if (!$destinationCompte) {
                    $db->transRollback();
                    return false;
                }

                $newDestinationSolde = (float) $destinationCompte['solde'] + $recipient['montant'];

                $db->table('comptes')
                    ->where('numero_telephone', $recipient['numero_telephone'])
                    ->update(['solde' => $newDestinationSolde]);
            }

            $newSourceSolde -= $totalDebit;

            $db->table('comptes')
                ->where('numero_telephone', $numeroTelephone)
                ->update(['solde' => $newSourceSolde]);
        } else {
            $db->transRollback();
            return false;
        }

        $db->transComplete();

        return $db->transStatus();
    }

    public function buildTransferPlan(float $montant, $destinataires, bool $inclureFrais = false, float $frais = 0.0): array
    {
        $destinatairesList = $this->parseDestinataires($destinataires);

        if ($destinatairesList === []) {
            return [];
        }

        $count = count($destinatairesList);
        $totalCents = (int) round($montant * 100);
        $baseCents = intdiv($totalCents, $count);
        $remainderCents = $totalCents % $count;

        $plan = [];

        foreach ($destinatairesList as $index => $numero) {
            $montantCents = $baseCents + ($index < $remainderCents ? 1 : 0);

            $plan[] = [
                'numero_telephone' => $numero,
                'montant' => round($montantCents / 100, 2),
                'frais' => $inclureFrais ? round($frais, 2) : 0.0,
            ];
        }

        return $plan;
    }

    public function parseDestinataires($destinataires): array
    {
        if (empty($destinataires)) {
            return [];
        }

        if (is_array($destinataires)) {
            $numbers = $destinataires;
        } else {
            $numbers = preg_split('/[\s,;]+/', trim((string) $destinataires)) ?: [];
        }

        return array_values(array_filter(array_map('trim', $numbers), static fn ($numero) => $numero !== ''));
    }

    public function getFraisApplicable(string $typeOperationCode, float $montant): float
    {
        $db = \Config\Database::connect();
        $typeOperation = $db->table('types_operation')
            ->where('code', $typeOperationCode)
            ->get()
            ->getRowArray();

        if (!$typeOperation) {
            return 0.0;
        }

        $trancheModel = new TrancheFraisModel();

        return $trancheModel->getFraisApplicable((int) $typeOperation['id'], $montant);
    }

    public function exists($numero_telephone)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('comptes');
        $builder->where('numero_telephone', $numero_telephone);

        return $builder->countAllResults() > 0;
    }
}
