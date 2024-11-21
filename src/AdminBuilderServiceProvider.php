<?php

namespace CuongPham2107\AdminBuilder;

use CuongPham2107\AdminBuilder\Services\Database\DatabaseServiceInterface;
use CuongPham2107\AdminBuilder\Services\Database\MySqlDatabaseService;
use CuongPham2107\AdminBuilder\Services\Database\SQLiteDatabaseService;

use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use CuongPham2107\AdminBuilder\Testing\TestsAdminBuilder;
use Illuminate\Support\Facades\DB;

class AdminBuilderServiceProvider extends PackageServiceProvider
{
    public static string $name = 'admin-builder';

    public static string $viewNamespace = 'admin-builder';

    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package->name(static::$name)
            ->hasCommands($this->getCommands())
            ->hasInstallCommand(function (InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('cuongpham2107/admin-builder');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../database/migrations'))) {
            $package->hasMigrations($this->getMigrations());
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }

        if (file_exists($package->basePath('/../resources/views'))) {
            $package->hasViews(static::$viewNamespace);
        }
    }

    public function packageRegistered(): void {}

    public function packageBooted(): void
    {
        // Database Service Binding
        $this->app->bind(DatabaseServiceInterface::class, function($app){
            if(DB::getDriverName() === 'sqlite'){
                return new SQLiteDatabaseService();
            }
            else{
                return new MySqlDatabaseService();
            }
        });
        //Migration registration
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__ . '/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/admin-builder/{$file->getFilename()}"),
                ], 'admin-builder-stubs');
            }
        }
        // Testing
        Testable::mixin(new TestsAdminBuilder);

    }

    protected function getAssetPackageName(): ?string
    {
        return 'cuongpham2107/admin-builder';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            // AlpineComponent::make('admin-builder', __DIR__ . '/../resources/dist/components/admin-builder.js'),
            Css::make('admin-builder-styles', __DIR__ . '/../resources/dist/admin-builder.css'),
            Js::make('admin-builder-scripts', __DIR__ . '/../resources/dist/admin-builder.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            // AdminBuilderCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getMigrations(): array
    {
        return [
            __DIR__ . '/../database/migrations',
        ];
    }
}
