<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'coins',
        'user_id',
    ];

    /**
     * Raise user coins
     */
    public static function raiseAllUserCoins($maxCoins, $coinsPerDay): bool
    {
        try {
            DB::beginTransaction();

            $query1 = /** @lang sql */
                'UPDATE user_balances SET coins = ? WHERE coins + ? > ?';
            DB::statement($query1, [$maxCoins, $coinsPerDay, $maxCoins]);

            $query = /** @lang sql */
                'UPDATE user_balances SET coins = coins + ? WHERE coins < ?';
            DB::statement($query, [$coinsPerDay, $maxCoins]);

            DB::commit();

            return true;
        } catch (\Except $exception) {
            logger($exception->getTraceAsString());
            DB::rollBack();
        }

        return false;
    }
}
