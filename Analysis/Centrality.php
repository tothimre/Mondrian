<?php

/*
 * Mondrian
 */

namespace Trismegiste\Mondrian\Analysis;

use Trismegiste\Mondrian\Graph\Algorithm;
use Trismegiste\Mondrian\Graph\Vertex;
use Trismegiste\Mondrian\Graph\Graph;
use Trismegiste\Mondrian\Graph\PowerIteration;
use Trismegiste\Mondrian\Graph\ReversedDigraph;

/**
 * Centrality analyses a graph and add centrality rank to vertices
 * Design Pattern : Decorator of Graph
 * 
 * What is the point of this tool ?
 * 
 * Searching for the degree of a vertex (outer and inner) is not enough
 * because you could have a vertex with many edges but with low impact
 * on source code if this vertex is not in the center of the graph.
 * That's why I use the eigenvector of the digraph like Google does in his
 * pagerank (sort of)
 * 
 * This tool helps you to find two effects on some components:
 * 
 * * The ripple effect
 * One component (class, parameter, method...) can be highly used accross
 * the source code. Each time there is a change in this component, chances
 * are you need to change many other components directly depending on it 
 * and so on. That's the ripple. With the "usedRank" method you can view
 * what component is time consuming and can lead to many merge conflicts in
 * Git.
 * 
 * * The bottleneck effect
 * Do you remeber this project where everytime you made a change somewhere,
 * THAT class need to be modified too ? The "dependRank" method finds this 
 * kind of problems. It searchs for the depencencies, but not only direct
 * dependencies but also the combination of dependencies accross all the
 * vertices of a digraph. My recomandation : abstract this component first :
 * make multiple interfaces, explode it with strategy pattern, decorator, 
 * CoR etc... All bugs are "drown" to this component like a blackhole.
 */
class Centrality extends Algorithm
{

    public function addDependRank()
    {
        $this->addCentralityRank($this->graph, 'centrality');
    }

    public function addUsedRank()
    {
        $reversed = new ReversedDigraph($this->graph);
        $this->addCentralityRank($reversed->getReversed(), 'centrality');
    }

    protected function addCentralityRank(Graph $g, $metaName)
    {
        $power = new PowerIteration($g);
        $eigen = $power->getEigenVectorSparse();

        $eigenVector = $eigen['vector'];
        $eigenArray = array();
        $vertexArray = array();
        foreach ($eigenVector as $idx => $v) {
            $eigenArray[$idx] = $eigenVector->getInfo();
            $vertexArray[$idx] = $v;
        }
        arsort($eigenArray);
        // only the order is interesting
        $iter = 0;
        $mostCentral = count($eigenVector) / 10.0;
        foreach ($eigenArray as $idx => $val) {
            $pseudoRank = ($iter < $mostCentral) ? 1 + (10.0 * $iter) / $mostCentral : 11;
            $vertexArray[$idx]->setMeta($metaName, $pseudoRank);
            $iter++;
        }
    }

}