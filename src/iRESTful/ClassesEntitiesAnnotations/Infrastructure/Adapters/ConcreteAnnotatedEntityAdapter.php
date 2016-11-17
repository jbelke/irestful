<?php
namespace iRESTful\ClassesEntitiesAnnotations\Infrastructure\Adapters;
use iRESTful\ClassesEntitiesAnnotations\Domain\Adapters\AnnotatedEntityAdapter;
use iRESTful\Annotations\Domain\Adapters\AnnotationAdapter;
use iRESTful\ClassesEntitiesAnnotations\Infrastructure\Objects\ConcreteAnnotatedEntity;
use iRESTful\ClassesEntities\Domain\Adapters\EntityAdapter;
use iRESTful\Classes\Domain\Samples\Adapters\SampleAdapter;
use iRESTful\DSLs\Domain\Projects\Objects\Entities\Entity;
use iRESTful\DSLs\Domain\Projects\Objects\Entities\Samples\Adapters\SampleAdapter as DSLSampleAdapter;

final class ConcreteAnnotatedEntityAdapter implements AnnotatedEntityAdapter {
    private $dslSampleAdapter;
    private $sampleAdapter;
    private $entityAdapter;
    private $annotationAdapter;
    public function __construct(DSLSampleAdapter $dslSampleAdapter, SampleAdapter $sampleAdapter, EntityAdapter $entityAdapter, AnnotationAdapter $annotationAdapter) {
        $this->dslSampleAdapter = $dslSampleAdapter;
        $this->sampleAdapter = $sampleAdapter;
        $this->entityAdapter = $entityAdapter;
        $this->annotationAdapter = $annotationAdapter;
    }

    public function fromDSLEntityToAnnotatedEntity(Entity $dslEntity) {
        $entity = $this->entityAdapter->fromDSLEntityToEntity($dslEntity);
        $annotation = $this->annotationAdapter->fromEntityToAnnotation($entity);

        $dslSample = $dslEntity->getSample();
        $data = $this->dslSampleAdapter->fromSampleToData($dslSample);
        array_walk($data, function(&$element, $index) use(&$annotation) {
            $element = [
                'container' => $annotation->getContainerName(),
                'data' => $element
            ];
        });

        $samples = $this->sampleAdapter->fromDataToSamples($data);
        return new ConcreteAnnotatedEntity($entity, $annotation, $samples);
    }

    public function fromDSLEntitiesToAnnotatedEntities(array $entities) {
        $output = [];
        foreach($entities as $oneEntity) {
            $output[] = $this->fromDSLEntityToAnnotatedEntity($oneEntity);
        }

        return $output;
    }

}
