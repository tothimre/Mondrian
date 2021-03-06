<?php

/*
 * Mondrian
 */

namespace Trismegiste\Mondrian\Graph;

/**
 * Vertex is a vertex of a graph
 */
class Vertex
{

    protected $name;

    /**
     * Construct the vertex with its name (preferably unique)
     *
     * @param string $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

}
