<?php

namespace App\Models;

use CodeIgniter\Model;

class TransactionModel extends Model
{
    protected $table         = 'transactions';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'reference',
        'compte_id',
        'compte_destinataire_id',
        'type_operation_id',
        'montant',
        'frais',
        'solde_avant',
        'solde_apres',
        'statut',
    ];

    public function genererReference(): string
    {
        return 'TXN-' . date('Ymd') . '-' . str_pad((string) random_int(1, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Situation des gains de l'opérateur via les frais.
     * Utilise la vue SQL "vue_gains" définie dans base.sql.
     */
    public function getGains(): array
    {
        return $this->db->query('SELECT * FROM vue_gains')->getResultArray();
    }

    public function getGainTotal(): float
    {
        $gains = $this->getGains();
        $total = 0;

        foreach ($gains as $g) {
            $total += (float) $g['total_gain'];
        }

        return $total;
    }

    /**
     * Historique complet et lisible des transactions.
     * Utilise la vue SQL "vue_historique_transactions".
     */
    public function getHistorique(?int $compteId = null): array
    {
        $sql = 'SELECT * FROM vue_historique_transactions';

        if ($compteId !== null) {
            $sql .= ' WHERE numero_emetteur = (SELECT numero_telephone FROM comptes WHERE id = ?)';
            return $this->db->query($sql, [$compteId])->getResultArray();
        }

        return $this->db->query($sql)->getResultArray();
    }
}
