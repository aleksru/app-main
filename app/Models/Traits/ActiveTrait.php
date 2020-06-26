<?php


namespace App\Models\Traits;


use App\Scopes\ActiveScope;
use Illuminate\Database\Eloquent\Builder;

trait ActiveTrait
{
    /**
     * boot trait
     */
    protected static function bootActiveTrait()
    {
        static::addGlobalScope(new ActiveScope());
    }

    /**
     * Disable trait
     * @return Builder
     */
    public static function withoutIsActive(): Builder
    {
        return self::withoutGlobalScope(ActiveScope::class);
    }

    /**
     * Scope only disables
     * @return Builder
     */
    public static function onlyDisables(): Builder
    {
        return self::withoutIsActive()->where('is_active', 0);
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return (bool)$this->is_active;
    }

    public function setActivated()
    {
        $this->is_active = 1;
    }
}