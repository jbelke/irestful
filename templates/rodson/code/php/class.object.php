{% autoescape false %}
{% import "includes/imports.class.php" as fn %}
<?php
namespace {{object.namespace.path}};
use {{object.interface.namespace.all}};

{% for oneParameter in object.constructor.parameters %}
    {% if oneParameter.parameter.type.namespace.all %}
        use {{oneParameter.parameter.type.namespace.all}};
    {% endif %}
{% endfor %}

{% for oneParameter in object.custom_methods.parameters %}
    {% if oneParameter.type.namespace.all %}
        use {{oneParameter.type.namespace.all}};
    {% endif %}
{% endfor %}

final class {{object.namespace.name}} implements {{object.interface.namespace.name}} {
    {{ fn.generateClassProperties(object.constructor.parameters) }}

    {{ fn.generateConstructorAnnotationParameters(parameters) }}
    public function __construct({{- fn.generateConstructorSignature(object.constructor.parameters) }}) {
        {{ fn.generateConstructorComboCustomMethod(object.constructor) }}
        {{ fn.generateAssignment(object.constructor.parameters) }}
    }

    {{ fn.generateGetters(object.constructor.parameters) }}
    {{ fn.generateCustomMethods(object.custom_methods) }}

}
{% endautoescape %}
