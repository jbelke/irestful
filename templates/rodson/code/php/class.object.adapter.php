{% autoescape false %}
{% import "includes/imports.class.php" as fn %}
<?php
namespace {{namespace.path}};
use {{interface.namespace.all}};
use iRESTful\LeoPaul\Objects\Libraries\Objects\Exceptions\ObjectException;

final class {{namespace.name}} implements {{interface.namespace.name}} {
    {{ fn.generateClassProperties(constructor.parameters) }}

    public function __construct({{- fn.generateConstructorSignature(constructor.parameters) }}) {
        {{ fn.generateAssignment(constructor.parameters) }}
    }

    {{ fn.generateCustomMethods(custom_methods) }}

}
{% endautoescape %}
