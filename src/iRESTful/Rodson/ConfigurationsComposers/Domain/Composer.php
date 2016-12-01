<?php
namespace iRESTful\Rodson\ConfigurationsComposers\Domain;

interface Composer {
    public function getName();
    public function getType();
    public function getHomepage();
    public function getLicense();
    public function getAuthors();
    public function getBaseNamespace();
    public function getBaseFolder();
    public function hasInstallation();
    public function getInstallation();
}
