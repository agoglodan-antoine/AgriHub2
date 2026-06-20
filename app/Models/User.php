<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Le nom de la table associée à ce modèle.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Les attributs qui sont mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_type_user',
        'nom',
        'prenom',
        'departement',
        'commune',
        'ville',
        'email',
        'mot_de_passe_hash',
        'telephone',
        'adresse',
        'date_inscription',
        'statut',
        'email_verified_at',
        'remember_token',
    ];

    /**
     * Les attributs qui doivent être cachés pour la sérialisation.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'mot_de_passe_hash',
        'remember_token',
    ];

    /**
     * Les attributs qui doivent être castés.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'date_inscription' => 'datetime',
            'mot_de_passe_hash' => 'hashed',
        ];
    }

    /**
     * Obtenir le mot de passe pour l'authentification.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->mot_de_passe_hash;
    }

    /**
     * Obtenir le nom complet de l'utilisateur.
     *
     * @return string
     */
    public function getNomCompletAttribute(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

    // ============================================
    // RELATIONS
    // ============================================

    /**
     * Relation avec le type d'utilisateur.
     */
    public function typeUser()
    {
        return $this->belongsTo(TypeUser::class, 'id_type_user');
    }

    /**
     * Relation avec le profil éleveur.
     */
    public function eleveur()
    {
        return $this->hasOne(Eleveur::class, 'id_user');
    }

    /**
     * Relation avec le profil acheteur.
     */
    public function acheteur()
    {
        return $this->hasOne(Acheteur::class, 'id_user');
    }

    /**
     * Relation avec le profil vétérinaire.
     */
    public function veterinaire()
    {
        return $this->hasOne(Veterinaire::class, 'id_user');
    }

    /**
     * Relation avec le profil transporteur.
     */
    public function transporteur()
    {
        return $this->hasOne(Transporteur::class, 'id_user');
    }

    /**
     * Relation avec le profil vendeur de nourriture.
     */
    public function vendeurNourriture()
    {
        return $this->hasOne(VendeurNourriture::class, 'id_user');
    }

    /**
     * Relation avec le profil vendeur d'accessoires.
     */
    public function vendeurAccessoire()
    {
        return $this->hasOne(VendeurAccessoire::class, 'id_user');
    }

    /**
     * Relation avec le profil administrateur.
     */
    public function admin()
    {
        return $this->hasOne(Admin::class, 'id_user');
    }

    /**
     * Relation avec les animaux possédés.
     */
    public function animaux()
    {
        return $this->hasMany(Animal::class, 'id_user');
    }

    /**
     * Relation avec les annonces publiées.
     */
    public function annonces()
    {
        return $this->hasMany(Annonce::class, 'id_user');
    }

    /**
     * Relation avec les messages envoyés.
     */
    public function messagesEnvoyes()
    {
        return $this->hasMany(Message::class, 'id_expediteur');
    }

    /**
     * Relation avec les messages reçus.
     */
    public function messagesRecus()
    {
        return $this->hasMany(Message::class, 'id_destinataire');
    }

    /**
     * Relation avec les rendez-vous comme vétérinaire.
     */
    public function rendezVousVeto()
    {
        return $this->hasMany(RendezVous::class, 'id_veterinaire');
    }

    /**
     * Relation avec les rendez-vous comme client.
     */
    public function rendezVousClient()
    {
        return $this->hasMany(RendezVous::class, 'id_client');
    }

    /**
     * Relation avec les points de fidélité.
     */
    public function pointsFidelite()
    {
        return $this->hasMany(PointFidelite::class, 'id_user');
    }

    /**
     * Relation avec les récompenses (many-to-many).
     */
    public function recompenses()
    {
        return $this->belongsToMany(Recompense::class, 'utilisateur_recompense', 'id_user', 'id_recompense')
                    ->withPivot('date_obtention', 'statut')
                    ->withTimestamps();
    }

    // ============================================
    // VÉRIFICATIONS DE RÔLES
    // ============================================

    /**
     * Vérifier si l'utilisateur est un éleveur.
     */
    public function isEleveur(): bool
    {
        return $this->typeUser && $this->typeUser->type === 'eleveur';
    }

    /**
     * Vérifier si l'utilisateur est un acheteur.
     */
    public function isAcheteur(): bool
    {
        return $this->typeUser && $this->typeUser->type === 'acheteur';
    }

    /**
     * Vérifier si l'utilisateur est un vétérinaire.
     */
    public function isVeterinaire(): bool
    {
        return $this->typeUser && $this->typeUser->type === 'veterinaire';
    }

    /**
     * Vérifier si l'utilisateur est un transporteur.
     */
    public function isTransporteur(): bool
    {
        return $this->typeUser && $this->typeUser->type === 'transporteur';
    }

    /**
     * Vérifier si l'utilisateur est un vendeur de nourriture.
     */
    public function isVendeurNourriture(): bool
    {
        return $this->typeUser && $this->typeUser->type === 'vendeur_nourriture';
    }

    /**
     * Vérifier si l'utilisateur est un vendeur d'accessoires.
     */
    public function isVendeurAccessoire(): bool
    {
        return $this->typeUser && $this->typeUser->type === 'vendeur_accessoire';
    }

    /**
     * Vérifier si l'utilisateur est un administrateur.
     */
    public function isAdmin(): bool
    {
        return $this->admin()->exists();
    }

    /**
     * Vérifier si l'utilisateur est un super administrateur.
     */
    public function isSuperAdmin(): bool
    {
        return $this->admin && $this->admin->type_admin === 'super_admin';
    }

    /**
     * Vérifier si l'utilisateur est un administrateur secondaire.
     */
    public function isAdminSecondaire(): bool
    {
        return $this->admin && $this->admin->type_admin === 'admin_secondaire';
    }

    /**
     * Vérifier si le compte utilisateur est actif.
     */
    public function isActive(): bool
    {
        return $this->statut === 'actif';
    }

    /**
     * Vérifier si le compte utilisateur est suspendu.
     */
    public function isSuspended(): bool
    {
        return $this->statut === 'suspendu';
    }

    /**
     * Vérifier si le compte utilisateur est banni.
     */
    public function isBanned(): bool
    {
        return $this->statut === 'banni';
    }

    // ============================================
    // SCOPES
    // ============================================

    /**
     * Scope pour les utilisateurs actifs.
     */
    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }

    /**
     * Scope pour les utilisateurs par type.
     */
    public function scopeByType($query, string $type)
    {
        return $query->whereHas('typeUser', function ($q) use ($type) {
            $q->where('type', $type);
        });
    }

    /**
     * Scope pour les éleveurs.
     */
    public function scopeEleveurs($query)
    {
        return $query->byType('eleveur');
    }

    /**
     * Scope pour les acheteurs.
     */
    public function scopeAcheteurs($query)
    {
        return $query->byType('acheteur');
    }

    /**
     * Scope pour les vétérinaires.
     */
    public function scopeVeterinaires($query)
    {
        return $query->byType('veterinaire');
    }

    /**
     * Scope pour les transporteurs.
     */
    public function scopeTransporteurs($query)
    {
        return $query->byType('transporteur');
    }

    /**
     * Scope pour les administrateurs.
     */
    public function scopeAdmins($query)
    {
        return $query->whereHas('admin');
    }

    // ============================================
    // MÉTHODES UTILITAIRES
    // ============================================

    /**
     * Obtenir le profil spécifique de l'utilisateur.
     */
    public function getProfilAttribute()
    {
        if ($this->isEleveur()) {
            return $this->eleveur;
        }
        if ($this->isAcheteur()) {
            return $this->acheteur;
        }
        if ($this->isVeterinaire()) {
            return $this->veterinaire;
        }
        if ($this->isTransporteur()) {
            return $this->transporteur;
        }
        if ($this->isVendeurNourriture()) {
            return $this->vendeurNourriture;
        }
        if ($this->isVendeurAccessoire()) {
            return $this->vendeurAccessoire;
        }
        if ($this->isAdmin()) {
            return $this->admin;
        }
        return null;
    }

    /**
     * Obtenir le type d'utilisateur en texte.
     */
    public function getTypeLabelAttribute(): string
    {
        return $this->typeUser ? $this->typeUser->label : 'Inconnu';
    }

    /**
     * Obtenir le rôle principal de l'utilisateur.
     */
    public function getRoleAttribute(): string
    {
        if ($this->isSuperAdmin()) {
            return 'Super Administrateur';
        }
        if ($this->isAdmin()) {
            return 'Administrateur';
        }
        if ($this->isEleveur()) {
            return 'Éleveur';
        }
        if ($this->isAcheteur()) {
            return 'Acheteur';
        }
        if ($this->isVeterinaire()) {
            return 'Vétérinaire';
        }
        if ($this->isTransporteur()) {
            return 'Transporteur';
        }
        if ($this->isVendeurNourriture()) {
            return 'Vendeur de nourriture';
        }
        if ($this->isVendeurAccessoire()) {
            return 'Vendeur d\'accessoires';
        }
        return 'Utilisateur';
    }

    /**
     * Compter le nombre d'annonces actives.
     */
    public function getAnnoncesActivesCountAttribute(): int
    {
        return $this->annonces()->where('statut', 'active')->count();
    }

    /**
     * Compter le nombre total de points de fidélité.
     */
    public function getTotalPointsFideliteAttribute(): float
    {
        $gains = $this->pointsFidelite()
            ->where('type_operation', 'gain')
            ->where(function ($q) {
                $q->whereNull('date_expiration')
                    ->orWhere('date_expiration', '>', now());
            })
            ->sum('montant_points');

        $depenses = $this->pointsFidelite()
            ->where('type_operation', 'depense')
            ->sum('montant_points');

        return $gains - $depenses;
    }

    /**
     * Relation avec les commandes comme acheteur.
     */
    public function commandesAcheteur()
    {
        return $this->hasMany(Commande::class, 'id_acheteur');
    }

    /**
     * Relation avec les commandes comme vendeur.
     */
    public function commandesVendeur()
    {
        return $this->hasMany(Commande::class, 'id_vendeur');
    }

    /**
     * Relation avec les commandes comme transporteur.
     */
    public function commandesTransporteur()
    {
        return $this->hasMany(Commande::class, 'id_transporteur');
    }

    /**
     * Relation avec les paiements (via commandes).
     */
    public function paiements()
    {
        return $this->hasManyThrough(Paiement::class, Commande::class, 'id_acheteur', 'id_commande');
    }
}