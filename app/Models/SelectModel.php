<?php

namespace App\Models;

use CodeIgniter\Model;

class SelectModel extends Model
{
    public function getAllComptes(): array
    {
        $db = \Config\Database::connect();

        return $db->table('comptes')
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getCompteByNumeroTelephone(string $numeroTelephone): ?array
    {
        $db = \Config\Database::connect();

        $compte = $db->table('comptes')
            ->where('numero_telephone', $numeroTelephone)
            ->get()
            ->getRowArray();

        return $compte ?: null;
    }

    public function getAllTypesOperation(): array
    {
        $db = \Config\Database::connect();

        return $db->table('types_operation')
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();
    }

    public function getAllTranchesFrais(): array
    {
        $db = \Config\Database::connect();

        return $db->table('tranches_frais')
            ->orderBy('id', 'ASC')
            ->get()
            ->getResultArray();
    }
}
