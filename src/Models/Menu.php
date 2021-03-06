<?php

namespace Anacreation\Cms\Models;

use Anacreation\Cms\Contracts\CacheManageableInterface;
use Anacreation\Cms\Events\MenuDeleted;
use Anacreation\Cms\Events\MenuSaved;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class Menu extends Model implements CacheManageableInterface
{

    // Relation
    protected $dispatchesEvents = [
        'saved'   => MenuSaved::class,
        'deleted' => MenuDeleted::class,
    ];

    protected $fillable = [
        'name',
        'code',
    ];

    public function links(): Relation {
        return $this->hasMany(Link::class);
    }

    public function render(string $template = null, ...$args) {
        $template = $template ?? "cms::components.menu_template";

        $params = [
            'links' => $this->links()
                            ->active()
                            ->with([
                                       'children' => function($query) {
                                           return $query->active()->order()->with('children');
                                       },
                                   ])
                            ->order()
                            ->whereParentId(0)
                            ->get(),
            'level' => 0,
            'menu'  => $this,
        ];

        $params = $params + ['args' => $args];

        $html = view($template,
                     $params)->render();

        return "<?php echo {$html} ";
    }

    public static function renderWithCode(
        string $menuCode, string $template = null, ...$args
    ) {
        $instance = static::whereCode($menuCode)->firstOrFail();
        $key = $instance->getCacheKey();

        return Cache::has($key) ? Cache::get($key): Cache::rememberForever($key,
            function() use ($instance, $template, $args) {
                return $instance->render($template,
                                         $args);
            });
    }

    public function scopeActive(Builder $query): Builder {
        return $query->whereIsActive(true);
    }

    public function getCacheKey(): string {
        return "menu_{$this->code}";
    }


    // Helpers

    public static function getLinksInMenu(string $menuCode): Collection {
        if($menu = static::whereCode($menuCode)->first()) {

            return cache()->rememberForever($menu->getCacheKey(),
                function() use ($menu) {
                    return $menu->links()
                                ->whereIsActive(true)
                                ->where('parent_id',
                                        0)
                                ->orderBy('order')
                                ->get()
                        ->each->getActiveChildren();
                });

        }

        return new Collection();
    }

}
