<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->enum('applies_to', ['all', 'egg', 'hen'])->default('all')->after('condition_value');
            $table->enum('reward_kind', ['fixed', 'percent'])->default('fixed')->after('applies_to');
        });

        // Reinterpret legacy reward_type/reward_unit into the new reward_kind,
        // preserving every other field on each existing offer.
        foreach (DB::table('offers')->get() as $offer) {
            $rewardKind  = 'fixed';
            $rewardValue = $offer->reward_value;

            if ($offer->reward_type === 'cashback') {
                $rewardKind = 'fixed';
            } elseif ($offer->reward_type === 'discount') {
                // Legacy "discount" offers were always meant as a percentage
                // (the reward_unit enum never actually supported storing '%').
                $rewardKind = 'percent';
            } else {
                // Legacy free-item rewards (egg/hen/free) had no reliable ₹
                // value — reset to 0 so the admin sets a real cashback amount.
                $rewardKind  = 'fixed';
                $rewardValue = 0;
            }

            DB::table('offers')->where('id', $offer->id)->update([
                'reward_kind'  => $rewardKind,
                'reward_value' => $rewardValue,
            ]);
        }

        Schema::table('offers', function (Blueprint $table) {
            $table->dropColumn(['reward_type', 'reward_unit']);
        });
    }

    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->string('reward_type')->nullable();
            $table->string('reward_unit')->nullable();
            $table->dropColumn(['applies_to', 'reward_kind']);
        });
    }
};
