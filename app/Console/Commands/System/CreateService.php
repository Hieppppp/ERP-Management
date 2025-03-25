<?php

namespace App\Console\Commands\System;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateService extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:service {service}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Service';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('service');
        $servicePath = app_path('Services') . '/';
        $namespace = "App\\Services\\";
        $parts = explode('/', $name);
        $objectName = count($parts) == 1 ? $parts[0] : str_replace('Service', '', array_pop($parts));
        $repositoryName = $objectName .'RepositoryInterface';
        $serviceName  = $objectName . 'Service';
        $namespace .= implode('\\', $parts);
        $serviceInterfaceName = $serviceName . 'Interface';
        $servicePath .= implode('/', $parts);
        if (!is_dir($servicePath)) {
            mkdir($servicePath, 0755, true);
        }
        $service = $servicePath . '/' . $serviceName . '.php';
        $serviceInterface = $servicePath . '/' . $serviceInterfaceName . '.php';
        $serviceCreate = [
            'namespace' => $namespace,
            'class' => $serviceName,
            'implements' => $serviceInterfaceName,
            'repositoryName' => $repositoryName,
            'repository' =>  strtolower(substr($objectName, 0, 1)) . substr($objectName, 1) . "Repository",
            'objectName' => $objectName
        ];
        $serviceInterfaceCreate = [
            'namespace' => $namespace,
            'interfaces' => $serviceInterfaceName,
        ];
        $templateService = $this->getServiceTemplate($serviceCreate);
        $templateServiceInterface = $this->getServiceInterfaceTemplate($serviceInterfaceCreate);
        File::ensureDirectoryExists(dirname($service));
        File::put($service, $templateService);
        File::ensureDirectoryExists(dirname($serviceInterface));
        File::put($serviceInterface, $templateServiceInterface);
        $this->info('Repository created: ' . $serviceName . '.php');
        $this->info('Repository created: ' . $serviceInterfaceName . '.php');
    }

    protected function getServiceTemplate($dataReplace, $model = null)
    {
        ob_start();
        $template = view('stubs/service/service')->render();
        $template = str_replace('@', '?', $template);
        $template = str_replace('{namespace}', $dataReplace['namespace'], $template);
        $template = str_replace('{class}', $dataReplace['class'], $template);
        $template = str_replace('{implements}', $dataReplace['implements'], $template);
        $template = str_replace('{repositoryName}', $dataReplace['repositoryName'], $template);
        $template = str_replace('{useRepository}', 'App\\Repositories\\' . ucfirst($dataReplace['objectName']) . '\\' . $dataReplace['repositoryName'], $template);
        $template = str_replace('{repository}', $dataReplace['repository'], $template);
        return $template;
    }

    protected function getServiceInterfaceTemplate($dataReplace)
    {
        ob_start();
        $template = view('stubs/service/serviceInterface')->render();
        $template = str_replace('@', '?', $template);
        $template = str_replace('{namespace}', $dataReplace['namespace'], $template);
        $template = str_replace('{interfaces}', $dataReplace['interfaces'], $template);
        return $template;
    }
}
