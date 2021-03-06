<?php

/*
 * Mondrian
 */

namespace Trismegiste\Mondrian\Builder\Compiler;

use Trismegiste\Mondrian\Visitor;

/**
 * AbstractTraverser partly builds the compiler with a traverser
 */
abstract class AbstractTraverser implements BuilderInterface
{

    public function buildTraverser(Visitor\FqcnHelper $collector)
    {
        $traverser = new \PHPParser_NodeTraverser();
        $traverser->addVisitor($collector);

        return $traverser;
    }

}