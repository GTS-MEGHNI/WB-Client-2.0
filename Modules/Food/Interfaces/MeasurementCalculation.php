<?php

namespace Modules\Food\Interfaces;

interface MeasurementCalculation
{
    function performCalculation(): void;
    function measurementAsArray() : array|null;
}
