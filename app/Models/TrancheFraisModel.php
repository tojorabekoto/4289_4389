<?php

namespace App\Models;

use CodeIgniter\Model;

class TrancheFraisModel extends Model
{
    protected $table         = 'tranches_frais';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = [
        'type_operation_id',
        'montant_min',
        'montant_max',
        'frais',
        'pourcentage_autre_operateur',
        'actif',
    ];

    protected $validationRules = [
        'type_operation_id' => 'required|integer',
        'montant_min'       => 'required|numeric',
        'montant_max'       => 'required|numeric',
        'frais'             => 'required|numeric',
    ];

    /**
     * Récupère toutes les tranches avec le code et le libellé
     * du type d'opération auquel elles appartiennent.
     */
    public function getTranchesAvecType(): array
    {
        return $this->select('tranches_frais.*, types_operation.code, types_operation.libelle')
                    ->join('types_operation', 'types_operation.id = tranches_frais.type_operation_id')
                    ->orderBy('types_operation.code', 'ASC')
                    ->orderBy('tranches_frais.montant_min', 'ASC')
                    ->findAll();
    }

    /**
     * Trouve le frais applicable pour un montant donné et un type d'opération.
     * Utilisé côté client au moment de faire un retrait/transfert.
     */
    public function getFraisApplicable(int $typeOperationId, float $montant): float
    {
        $tranche = $this->where('type_operation_id', $typeOperationId)
                         ->where('montant_min <=', $montant)
                         ->where('montant_max >=', $montant)
                         ->first();

        return $tranche ? (float) $tranche['frais'] : 0.0;
    }
}
