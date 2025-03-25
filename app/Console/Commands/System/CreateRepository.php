<?php

namespace App\Console\Commands\System;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateRepository extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:repository {repository} {model?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Repositories';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('repository');
        $modelRepository = $this->argument('model');
        $repositoryPath = app_path('Repositories') . '/';
        $namespace = "App\\Repositories\\";
        $parts = explode('/', $name);
        $repositoryName  = count($parts) == 1 ? $parts[0] . 'Repository' : array_pop($parts);
        $namespace .= implode('\\', $parts);
        $repositoryInterfaceName = $repositoryName . 'Interface';
        $repositoryPath .= implode('/', $parts);
        if (!is_dir($repositoryPath)) {
            mkdir($repositoryPath, 0755, true);
        }

        $repository = $repositoryPath . '/' . $repositoryName . '.php';
        $repositoryInterface = $repositoryPath . '/' . $repositoryInterfaceName . '.php';
        $repositoryCreate = [
            'namespace' => $namespace,
            'class' => $repositoryName,
            'implements' => $repositoryInterfaceName,
        ];
        $repositoryInterfaceCreate = [
            'namespace' => $namespace,
            'interfaces' => $repositoryInterfaceName,
        ];
        $model = null;
        if ($modelRepository && class_exists("App\\Models\\" . $modelRepository)) {
            $model = "App\\Models\\" . $modelRepository;
            $repositoryCreate = [
                ...$repositoryCreate,
                'model' => $modelRepository,
            ];
        }

        $templateRepository = $this->getRepositoryTemplate($repositoryCreate, $model);
        $templateRepositoryInterface = $this->getRepositoryInterfaceTemplate($repositoryInterfaceCreate);
        if (File::exists($repository)) {
            $this->error(basename($repository) . " already exists!");
            return;
        }
        if (File::exists($repositoryInterface)) {
            $this->error(basename($repositoryInterface) . " already exists!");
            return;
        }
        File::ensureDirectoryExists(dirname($repository));
        File::put($repository, $templateRepository);
        File::ensureDirectoryExists(dirname($repositoryInterface));
        File::put($repositoryInterface, $templateRepositoryInterface);
        $this->info('Repository created: ' . $repositoryName . '.php');
        $this->info('Repository created: ' . $repositoryInterfaceName . '.php');
    }

    protected function getRepositoryTemplate($dataReplace, $model = null)
    {
        ob_start();
        $template = view('stubs/repository/repository', ['model' => $model])->render();
        $template = str_replace('@', '?', $template);
        $template = str_replace('{namespace}', $dataReplace['namespace'], $template);
        $template = str_replace('{class}', $dataReplace['class'], $template);
        $template = str_replace('{implements}', $dataReplace['implements'], $template);
        if ($model) {
            $template = str_replace('{useModel}', $model, $template);
            $template = str_replace('{nameModel}', ucfirst($dataReplace['model']), $template);
            $template = str_replace('{model}',strtolower(substr($dataReplace['model'], 0, 1)) . substr($dataReplace['model'], 1), $template);
        }
        return $template;
    }

    protected function getRepositoryInterfaceTemplate($dataReplace)
    {
        ob_start();
        $template = view('stubs/repository/repositoryInterface')->render();
        $template = str_replace('@', '?', $template);
        $template = str_replace('{namespace}', $dataReplace['namespace'], $template);
        $template = str_replace('{interfaces}', $dataReplace['interfaces'], $template);
        return $template;
    }
}
