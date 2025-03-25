<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class SyncTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync missing translation keys from English to French';

    protected $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $enPath = resource_path('lang/en');
        $frPath = resource_path('lang/fr');

        $enFiles = $this->files->files($enPath);
        foreach ($enFiles as $enFile) {
            $fileName = $enFile->getFilename();
            $enTranslations = include $enFile->getRealPath();

            $frFilePath = $frPath . '/' . $fileName;
            $frTranslations = $this->files->exists($frFilePath) ? include $frFilePath : [];

            $mergedTranslations = $this->mergeTranslations($enTranslations, $frTranslations);

            $this->files->put($frFilePath, $this->formatArrayAsReturn($mergedTranslations));
        }

        $this->info('Translation files have been synced.');
    }

    protected function mergeTranslations(array $enTranslations, array $frTranslations)
    {
        foreach ($enTranslations as $key => $value) {
            if (is_array($value)) {
                $frTranslations[$key] = $this->mergeTranslations($value, $frTranslations[$key] ?? []);
            } else {
                if (!array_key_exists($key, $frTranslations)) {
                    $frTranslations[$key] = $value;
                }
            }
        }

        return $frTranslations;
    }

    protected function formatArrayAsReturn(array $array)
    {
        $exportedArray = var_export($array, true);
        // Replace single quotes around array keys with double quotes
        $exportedArray = preg_replace("/'(\w+)' =>/", '"$1" =>', $exportedArray);

        // Replace single quotes around values with double quotes, avoiding those inside values
        $exportedArray = preg_replace_callback(
            "/'([^'\\\\]*(?:\\\\.[^'\\\\]*)*)'/",
            function ($matches) {
                return '"' . $matches[1] . '"';
            },
            $exportedArray
        );

        $exportedArray = preg_replace("/^([ ]*)array \($/m", '$1[', $exportedArray);
        $exportedArray = preg_replace("/^([ ]*)\)(,?)$/m", '$1]$2', $exportedArray);
        return "<?php\n\nreturn " . $exportedArray . ";\n";
    }
}
