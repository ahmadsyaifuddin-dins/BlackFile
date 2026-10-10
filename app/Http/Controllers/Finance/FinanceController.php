<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Base controller untuk modul Finance.
 *
 * Aturan akses:
 * - Setiap agen hanya boleh melihat & mengelola data keuangannya sendiri.
 * - Director (role tertinggi) boleh MELIHAT data keuangan agen lain, tetapi
 *   hanya read-only. Agen lain sama sekali tidak bisa melihat data agen lain.
 */
abstract class FinanceController extends Controller
{
    /**
     * Apakah user yang sedang login adalah Director.
     */
    protected function isDirector(): bool
    {
        return strtolower(optional(Auth::user()->role)->name ?? '') === 'director';
    }

    /**
     * Tentukan pemilik data yang sedang dilihat.
     *
     * @return array{0:int,1:\App\Models\User,2:bool} [ownerId, owner, viewingOther]
     */
    protected function resolveOwner(Request $request): array
    {
        $self = Auth::user();
        $owner = $self;
        $viewingOther = false;

        // Hanya Director yang boleh berpindah ke data agen lain.
        if ($this->isDirector() && $request->filled('agent')) {
            $candidate = User::find($request->input('agent'));

            if ($candidate && $candidate->id !== $self->id) {
                $owner = $candidate;
                $viewingOther = true;
            }
        }

        return [$owner->id, $owner, $viewingOther];
    }

    /**
     * Data konteks yang dikirim ke semua view Finance.
     */
    protected function financeViewData(Request $request): array
    {
        [$ownerId, $owner, $viewingOther] = $this->resolveOwner($request);

        return [
            'ownerId' => $ownerId,
            'owner' => $owner,
            'viewingOther' => $viewingOther,
            'readOnly' => $viewingOther,
            'agents' => $this->isDirector()
                ? User::orderBy('codename')->get()
                : collect(),
            'agentQuery' => $viewingOther ? '?agent='.$owner->id : '',
        ];
    }

    /**
     * Cegah perubahan data ketika Director sedang melihat data agen lain.
     */
    protected function denyIfReadOnly(array $data): void
    {
        if ($data['readOnly']) {
            abort(403, 'UNAUTHORIZED: READ-ONLY ACCESS.');
        }
    }

    /**
     * Pastikan model memang milik pemilik data yang sedang dilihat.
     */
    protected function ensureOwned($model, array $data): void
    {
        if ((int) $model->user_id !== (int) $data['ownerId']) {
            abort(403, 'UNAUTHORIZED ACCESS.');
        }
    }
}
