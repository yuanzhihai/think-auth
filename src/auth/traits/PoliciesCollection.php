<?php
declare ( strict_types = 1 );

namespace yzh52521\auth\traits;

trait PoliciesCollection
{

    public function withPolicies($abilities)
    {
        $this->each( function ($model) use ($abilities) {
            /** @var PoliciesModel $model */
            $model && $model->withPolicies( $abilities );
        } );

        return $this;
    }
}