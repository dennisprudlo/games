<?php

namespace App\Contracts;

/**
 * Defines that a model is aware of its references and can behave accordingly.
 */
interface ReferenceAware
{
    /**
     * Deletes the model with all of its references
     */
    public function deleteWithRefs(): void;
}
