<?php declare(strict_types=1);

namespace App\Models;

use App\Exceptions\NoLeftCoinsException;
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
     * Raise all user coins
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

    /**
     * Collect the money for publishing the post
     * @throws NoLeftCoinsException
     */
    public function keepForJobPosting() {
        $postingCost = intval(env('VACANCY_POST_COST'));
        $coins = intval($this->coins);

        if (($coins - $postingCost < 0)) {
            throw new NoLeftCoinsException;
        }

        $this->coins = $coins - $postingCost;
        return $this->save();
    }
}
