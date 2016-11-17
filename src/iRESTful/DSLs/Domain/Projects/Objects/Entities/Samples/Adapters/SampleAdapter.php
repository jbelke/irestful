<?php
namespace iRESTful\DSLs\Domain\Projects\Objects\Entities\Samples\Adapters;
use iRESTful\DSLs\Domain\Projects\Objects\Entities\Samples\Sample;

interface SampleAdapter {
    public function fromSampleToData(Sample $sample);
    public function fromDataToSamples(array $data);
}
