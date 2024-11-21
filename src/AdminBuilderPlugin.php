<?php

namespace CuongPham2107\AdminBuilder;

use Closure;
use Filament\Contracts\Plugin;
use Filament\Panel;

class AdminBuilderPlugin implements Plugin
{
    protected ?array $resource = null;
    public function getId(): string
    {
        return 'admin-builder';
    }

    public function register(Panel $panel): void
    {
        $panel->resources(
            $this->getResource()
        );
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public static function make(): static
    {
        return app(static::class);
    }

    public static function get(): static
    {
        /** @var static $plugin */
        $plugin = filament(app(static::class)->getId());

        return $plugin;
    }

    public function getResource(): array
    {
        return $this->resource ?? [
            \CuongPham2107\AdminBuilder\Resources\DataTableResource::class,
        ];
        
    }
   
}
