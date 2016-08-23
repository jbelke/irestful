<?php
namespace iRESTful\Rodson\Infrastructure\Applications;
use iRESTful\Rodson\Applications\RodsonApplication;
use iRESTful\Rodson\Infrastructure\Inputs\Factories\ConcreteRodsonRepositoryFactory;
use iRESTful\Rodson\Infrastructure\Middles\Factories\ConcreteClassAdapterFactory;
use iRESTful\Rodson\Infrastructure\Outputs\Factories\PHPCodeAdapterFactory;
use iRESTful\Rodson\Infrastructure\Outputs\Services\FileCodeService;
use iRESTful\Rodson\Infrastructure\Middles\Adapters\ConcreteAnnotatedClassAdapter;
use iRESTful\Rodson\Infrastructure\Middles\Factories\ConcreteAnnotationAdapterFactory;
use iRESTful\Rodson\Infrastructure\Middles\Adapters\ConcreteSampleAdapter;
use iRESTful\Rodson\Infrastructure\Middles\Adapters\ConcreteConfigurationAdapter;
use iRESTful\Rodson\Infrastructure\Middles\Factories\ConcreteConfigurationNamespaceFactory;
use iRESTful\Rodson\Infrastructure\Middles\Adapters\ConcreteFunctionalTransformTestAdapter;
use iRESTful\Rodson\Infrastructure\Middles\Adapters\ConcreteClassNamespaceAdapter;

final class PHPFileRodsonApplication implements RodsonApplication {
    private $baseNamespace;
    private $repository;
    private $service;
    public function __construct(array $baseNamespace) {

        $this->baseNamespace = $baseNamespace;

        $repositoryFactory = new ConcreteRodsonRepositoryFactory();
        $this->repository = $repositoryFactory->create();

        $this->service = new FileCodeService();
    }

    public function executeByFolder($folderPath, $outputFolderPath) {

    }

    public function executeByFile($filePath, $outputFolderPath) {
        $rodson = $this->repository->retrieve([
            'file_path' => $filePath
        ]);

        $name = $rodson->getName();

        $baseNamespace = array_merge($this->baseNamespace, [$name]);

        $classAdapterFactory = new ConcreteClassAdapterFactory($baseNamespace);
        $classAdapter = $classAdapterFactory->create();

        $annotationAdapterFactory = new ConcreteAnnotationAdapterFactory($baseNamespace);
        $annotationAdapter = $annotationAdapterFactory->create();

        $sampleAdapter = new ConcreteSampleAdapter();
        $annotatedClassAdapter = new ConcreteAnnotatedClassAdapter($classAdapter, $annotationAdapter, $sampleAdapter);
        $annotatedClasses = $annotatedClassAdapter->fromRodsonToAnnotatedClasses($rodson);

        $configurationNamespaceFactory = new ConcreteConfigurationNamespaceFactory($baseNamespace, $name);
        $configurationAdapter = new ConcreteConfigurationAdapter($configurationNamespaceFactory, '___', 'America/Montreal');
        $configuration = $configurationAdapter->fromAnnotatedClassesToConfiguration($annotatedClasses);

        $functionalTransformTestAdapter = new ConcreteFunctionalTransformTestAdapter(
            $baseNamespace,
            $configuration
        );

        $functionalTransformTests = $functionalTransformTestAdapter->fromAnnotatedClassesToTransformTests($annotatedClasses);

        $output = array_filter(explode('/', $outputFolderPath));
        $codeAdapterFactory = new PHPCodeAdapterFactory($output);
        $this->codeAdapter = $codeAdapterFactory->create();

        $codes = $this->codeAdapter->fromAnotatedClassesToCodes($annotatedClasses);
        $codes[] = $this->codeAdapter->fromConfigurationToCode($configuration);
        $codes = array_merge($codes, $this->codeAdapter->fromFunctionalTransformTestsToCodes($functionalTransformTests));

        $this->service->saveMultiple($codes);
    }

}
