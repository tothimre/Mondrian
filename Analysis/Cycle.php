<?php

/*
 * Mondrian
 */

namespace Trismegiste\Mondrian\Analysis;

use Trismegiste\Mondrian\Graph\Tarjan;

/**
 * Cycle analyses cycles by searching for strongly connected components
 */
class Cycle extends Tarjan
{

    protected $scc = null;
    protected $threshold = 3;

    public function getPartition()
    {
        $filtered = array();
        if (is_null($this->scc)) {
            $this->scc = $this->getStronglyConnected();
        }
        // filter small cycle
        foreach ($this->scc as $idx => $subgraph) {
            if (count($subgraph) >= $this->threshold) {
                $filtered[] = $subgraph;
            }
        }

        return $filtered;
    }

}
