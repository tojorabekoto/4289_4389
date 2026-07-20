<?php

namespace App\Models;

use CodeIgniter\Model;

class CompteModel extends Model
{
    protected $table         = 'comptes';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'numero_telephone',
        'solde',
        'statut',
        'derniere_activite',
    ];

    /**
     * Récupère un compte par numéro, ou le crée automatiquement
     * s'il n'existe pas encore (pas d'inscription préalable).
     */
    public function trouverOuCreer(string $numeroTelephone): array
    {
        $compte = $this->where('numero_telephone', $numeroTelephone)->first();

        if (! $compte) {
            $id = $this->insert([
                'numero_telephone'  => $numeroTelephone,
                'solde'             => 0,
                'statut'            => 'actif',
                'derniere_activite' => date('Y-m-d H:i:s'),
            ], true);

            $compte = $this->find($id);
        }

        return $compte;
    }

    /**
     * Vue d'ensemble : solde + nombre de transactions par client.
     * Utilise la vue SQL "vue_comptes_clients" définie dans base.sql.
     */
    public function getSituationComptes(): array
    {
        return $this->db->query('SELECT * FROM vue_comptes_clients ORDER BY solde DESC')
                         ->getResultArray();
    }

    public function crediter(int $compteId, float $montant): bool
    {
        $compte = $this->find($compteId);
        return $this->update($compteId, [
            'solde'             => $compte['solde'] + $montant,
            'derniere_activite' => date('Y-m-d H:i:s'),
        ]);
    }

    public function debiter(int $compteId, float $montant): bool
    {
        $compte = $this->find($compteId);
        return $this->update($compteId, [
            'solde'             => $compte['solde'] - $montant,
            'derniere_activite' => date('Y-m-d H:i:s'),
        ]);
    }
}
