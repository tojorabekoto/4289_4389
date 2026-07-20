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
        return $this->getPrefixePourNumeroTelephone($numeroTelephone) !== null;
    }

    public function getPrefixePourNumeroTelephone(string $numeroTelephone): ?string
    {
        $prefixesActifs = $this->where('actif', 1)
                               ->orderBy('LENGTH(prefixe)', 'DESC')
                               ->findAll();

        foreach ($prefixesActifs as $prefixe) {
            if (strpos($numeroTelephone, $prefixe['prefixe']) === 0) {
                return $prefixe['prefixe'];
            }
        }

        return null;
    }

    public function estUnAutreOperateur(string $numeroTelephoneSource, string $numeroTelephoneDestinataire): bool
    {
        $prefixeSource = $this->getPrefixePourNumeroTelephone($numeroTelephoneSource);
        $prefixeDestinataire = $this->getPrefixePourNumeroTelephone($numeroTelephoneDestinataire);

        return $prefixeSource !== null
            && $prefixeDestinataire !== null
            && $prefixeSource !== $prefixeDestinataire;
    }
}
