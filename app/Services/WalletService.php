<?php

namespace App\Services;

use App\Models\User;
use App\Models\WalletTransaction;
use RuntimeException;

class WalletService
{
    /**
     * Credit a user's wallet and record the transaction. Caller is
     * responsible for wrapping this in a DB transaction if needed.
     */
    public function credit(User $user, float $amount, string $description, ?string $referenceType = null, ?int $referenceId = null): WalletTransaction
    {
        if ($amount <= 0) {
            throw new RuntimeException('Credit amount must be greater than zero.');
        }

        $user->refresh();
        $user->wallet_balance = round($user->wallet_balance + $amount, 2);
        $user->save();

        return WalletTransaction::create([
            'user_id'        => $user->id,
            'type'           => 'credit',
            'amount'         => $amount,
            'balance_after'  => $user->wallet_balance,
            'description'    => $description,
            'reference_type' => $referenceType,
            'reference_id'   => $referenceId,
        ]);
    }

    /**
     * Debit a user's wallet (e.g. spending balance at checkout). Throws if
     * funds are insufficient so callers never overdraw the wallet.
     */
    public function debit(User $user, float $amount, string $description, ?string $referenceType = null, ?int $referenceId = null): WalletTransaction
    {
        if ($amount <= 0) {
            throw new RuntimeException('Debit amount must be greater than zero.');
        }

        $user->refresh();

        if ($amount > $user->wallet_balance) {
            throw new RuntimeException('Insufficient wallet balance.');
        }

        $user->wallet_balance = round($user->wallet_balance - $amount, 2);
        $user->save();

        return WalletTransaction::create([
            'user_id'        => $user->id,
            'type'           => 'debit',
            'amount'         => $amount,
            'balance_after'  => $user->wallet_balance,
            'description'    => $description,
            'reference_type' => $referenceType,
            'reference_id'   => $referenceId,
        ]);
    }
}
