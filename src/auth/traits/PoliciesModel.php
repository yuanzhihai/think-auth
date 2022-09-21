<?php

namespace yzh52521\auth\traits;

use yzh52521\auth\Collection;
use yzh52521\facade\Gate;

trait PoliciesModel
{
    public function withPolicies($abilities)
    {
        if (is_string( $abilities )) {
            $abilities = explode( ',',$abilities );
        }

        $this->withAttr( 'policies',function () use ($abilities) {
            $data = [];
            foreach ( $abilities as $ability ) {
                $data[$ability] = Gate::can( $ability,$this );
            }
            return $data;
        } );

        $this->append( ['policies'] );

        return $this;
    }

    public function toCollection(iterable $collection = [],string $resultSetType = null): \think\Collection
    {
        return parent::toCollection( $collection,Collection::class );
    }
}
