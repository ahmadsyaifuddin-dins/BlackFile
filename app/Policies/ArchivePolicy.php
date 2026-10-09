<?php

namespace App\Policies;

use App\Models\Archive;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ArchivePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Archive $archive): bool
    {
        // Jika user adalah Director, selalu izinkan.
        if ($this->isDirector($user)) {
            return true;
        }

        // Jika user adalah Agent, izinkan jika arsipnya public ATAU miliknya sendiri.
        return $archive->is_public || $user->id === $archive->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Archive $archive): bool
    {
        // Logikanya sama: user ID harus cocok
        return $user->id === $archive->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * Director adalah otoritas tertinggi, jadi boleh menghapus arsip milik
     * agent mana pun. Selain itu, pemilik arsipnya sendiri.
     */
    public function delete(User $user, Archive $archive): bool
    {
        if ($this->isDirector($user)) {
            return true;
        }

        return $user->id === $archive->user_id;
    }

    /**
     * Determine whether the user can manage public sharing (toggle public/ad).
     * Director boleh mengatur semua archive; pemilik hanya arsipnya sendiri.
     */
    public function share(User $user, Archive $archive): bool
    {
        if ($this->isDirector($user)) {
            return true;
        }

        return $user->id === $archive->user_id;
    }

    /**
     * Apakah user punya role Director (otoritas tertinggi).
     *
     * Dicek null-safe karena user tanpa role (mis. data belum lengkap)
     * tidak boleh dianggap Director.
     */
    private function isDirector(User $user): bool
    {
        return strtolower((string) ($user->role->name ?? '')) === 'director';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Archive $archive): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Archive $archive): bool
    {
        //
    }
}
