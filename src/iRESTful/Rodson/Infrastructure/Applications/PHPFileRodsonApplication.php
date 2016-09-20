<?php
namespace iRESTful\Rodson\Infrastructure\Applications;
use iRESTful\Rodson\Applications\RodsonApplication;
use iRESTful\Rodson\Infrastructure\Inputs\Factories\ConcreteRodsonRepositoryFactory;
use iRESTful\Rodson\Infrastructure\Middles\Factories\ConcreteSpecificClassAdapterFactory;
use iRESTful\Rodson\Infrastructure\Outputs\Services\FileCodeService;
use iRESTful\Rodson\Infrastructure\Middles\Adapters\ConcreteAnnotatedClassAdapter;
use iRESTful\Rodson\Infrastructure\Middles\Factories\ConcreteAnnotationAdapterFactory;
use iRESTful\Rodson\Infrastructure\Middles\Adapters\ConcreteSampleAdapter;
use iRESTful\Rodson\Infrastructure\Middles\Adapters\ConcreteConfigurationAdapter;
use iRESTful\Rodson\Infrastructure\Middles\Factories\ConcreteConfigurationNamespaceFactory;
use iRESTful\Rodson\Infrastructure\Middles\Adapters\ConcreteFunctionalTransformTestAdapter;
use iRESTful\Rodson\Infrastructure\Middles\Adapters\ConcreteClassNamespaceAdapter;
use iRESTful\Rodson\Infrastructure\Outputs\Factories\TwigTemplateFactory;
use iRESTful\Rodson\Infrastructure\Outputs\Adapters\ConcreteCodeAdapter;
use iRESTful\Rodson\Infrastructure\Outputs\Adapters\ConcreteOutputCodePathAdapter;
use iRESTful\Rodson\Infrastructure\Outputs\Adapters\ConcreteOutputCodeFileAdapter;
use iRESTful\Rodson\Infrastructure\Middles\Adapters\ConcreteClassControllerAdapterAdapter;
use iRESTful\Rodson\Infrastructure\Middles\Adapters\ConcreteSpecificClassEntityAnnotatedAdapter;
use iRESTful\Rodson\Infrastructure\Middles\Adapters\ConcreteComposerAdapter;
use iRESTful\Rodson\Infrastructure\Middles\Adapters\ConcreteVagrantFileAdapter;
use iRESTful\Rodson\Infrastructure\Middles\Adapters\ConcretePHPUnitAdapter;

final class PHPFileRodsonApplication implements RodsonApplication {
    private $baseNamespace;
    private $templateFolder;
    private $cacheFolder;
    private $baseFolder;
    public function __construct($templateFolder, $cacheFolder = null, $baseFolder = 'src') {
        $this->templateFolder = $templateFolder;
        $this->cacheFolder = $cacheFolder;
        $this->baseFolder = $baseFolder;
    }

    public function executeByFolder($folderPath, $outputFolderPath) {

    }

    public function executeByFile($filePath, $outputFolderPath) {

        $repositoryFactory = new ConcreteRodsonRepositoryFactory();
        $repository = $repositoryFactory->create();
        $rodson = $repository->retrieve($filePath);

        $name = $rodson->getName();
        $baseNamespace = [
            $name->getOrganizationName(),
            $name->getProjectName()
        ];

        $classAdapterFactory = new ConcreteSpecificClassAdapterFactory(
            $baseNamespace,
            '___',
            'America/Montreal'
        );

        $classAdapter = $classAdapterFactory->create();
        $classes = $classAdapter->fromRodsonToClasses($rodson);

        $composerAdapter = new ConcreteComposerAdapter($this->baseFolder);
        $composer = $composerAdapter->fromRodsonToComposer($rodson);

        $vagrantFileAdapter = new ConcreteVagrantFileAdapter();
        $vagrantFile = $vagrantFileAdapter->fromRodsonToVagrantFile($rodson);

        $phpunitAdapter = new ConcretePHPUnitAdapter();
        $phpunit = $phpunitAdapter->fromRodsonToPHPUnit($rodson);

        $twigTemplateFactory = new TwigTemplateFactory($this->templateFolder, $this->cacheFolder);

        $template = $twigTemplateFactory->create();
        $fileAdapter = new ConcreteOutputCodeFileAdapter();

        $classOutputPath = explode('/', $outputFolderPath);
        if (!empty($this->baseFolder)) {
            $baseFolderPath = explode('/', $this->baseFolder);
            $classOutputPath = array_merge($classOutputPath, $baseFolderPath);
        }

        $classOutput = array_filter($classOutputPath);
        $classPathAdapter = new ConcreteOutputCodePathAdapter($fileAdapter, $classOutput);
        $classCodeAdapter = new ConcreteCodeAdapter($classPathAdapter, $template);
        $classCodes = $classCodeAdapter->fromClassesToCodes($classes);

        $rootOutput = array_filter(explode('/', $outputFolderPath));
        $rootPathAdapter = new ConcreteOutputCodePathAdapter($fileAdapter, $rootOutput);
        $rootCodeAdapter = new ConcreteCodeAdapter($rootPathAdapter, $template);
        $composerCode = $rootCodeAdapter->fromComposerToCode($composer);
        $vagrantFileCode = $rootCodeAdapter->fromVagrantFileToCode($vagrantFile);
        $phpunitCode = $rootCodeAdapter->fromPHPUnitToCode($phpunit);


        $service = new FileCodeService();
        $service->saveMultiple(array_merge($classCodes, [$composerCode, $vagrantFileCode, $phpunitCode]));
    }

}
