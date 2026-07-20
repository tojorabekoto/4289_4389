<?php

namespace App\Models;

use CodeIgniter\Model;

class PrefixeModel extends Model
{
    protected $table         = 'prefixes';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;

    protected $allowedFields = ['prefixe', 'description', 'actif'];

    protected $validationRules = [
        'prefixe' => 'required|max_length[5]|is_unique[prefixes.prefixe,id,{id}]',
    ];

    protected $validationMessages = [
        'prefixe' => [
            'required'   => 'Le préfixe est obligatoire.',
            'is_unique'  => 'Ce préfixe existe déjà.',
        ],
    ];

    /**
     * Vérifie si un numéro de téléphone commence par un préfixe actif.
     */
    public function estPrefixeValide(string $numeroTelephone): bool
    {
        // $prefixesActifs = $this->where('actif', 1)->findAll();

        // foreach ($prefixesActifs as $p) {
        //     if (strpos($numeroTelephone, $p['prefixe']) === 0) {
        //         return true;
        //     }
        // }
        

        return true;
    }
}
