<?php
namespace iRESTful\Applications\Infrastructure\Factories;
use iRESTful\Applications\Domain\Factories\ApplicationFactory;
use iRESTful\Applications\Infrastructure\Adapters\ConcreteDomainApplicationAdapter;
use iRESTful\DSLs\Domain\DSL;

final class ConcreteDomainApplicationFactory implements ApplicationFactory {
    private $dsl;
    private $timezone;
    private $templatePath;
    private $outputFolderPath;
    private $codeDirectory;
    private $webDirectory;
    public function __construct(DSL $dsl, string $timezone, string $templatePath, string $outputFolderPath, string $codeDirectory, string $webDirectory) {
        $this->dsl = $dsl;
        $this->timezone = $timezone;
        $this->templatePath = $templatePath;
        $this->outputFolderPath = $outputFolderPath;
        $this->codeDirectory = $codeDirectory;
        $this->webDirectory = $webDirectory;
    }

    public function create() {
        $domainApplicationAdapter = new ConcreteDomainApplicationAdapter(
            $this->timezone,
            $this->templatePath,
            $this->outputFolderPath,
            $this->codeDirectory,
            $this->webDirectory
        );
        
        return $domainApplicationAdapter->fromDSLToApplication($this->dsl);
    }

}
