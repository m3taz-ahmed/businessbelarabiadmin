<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Filament\Forms\Components\FileUpload;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // No morph map needed - using full class names (FQCN) directly
        URL::forceScheme('https');


        FileUpload::macro('s3Directory', function (string $directory = 'uploads') {
            /** @var FileUpload $this */
            return $this->disk('s3')
                ->saveUploadedFileUsing(function (TemporaryUploadedFile $file, $set) use ($directory) {
                    $filename = $directory . '/' . Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('', $filename, 's3');
                    $set('file_path', $path);
                    return $path;
                });
        });
    }
}
