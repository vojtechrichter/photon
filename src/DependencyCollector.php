<?php

declare(strict_types=1);

namespace Vojtechrichter\StaticPhpDepCompiler;

use PhpParser\Node;
use PhpParser\NodeVisitorAbstract;

final class DependencyCollector extends NodeVisitorAbstract
{
    /** @var array<non-empty-string, class-string> */
    private array $dependsOn = [];
    private ?string $currentFile = null;
    private ?string $currentNamespace = null;
    private array $declaredInFile = [];

    public function setCurrentFile(string $file): void
    {
        $this->currentFile = $file;
        $this->declaredInFile = [];
    }

    public function enterNode(Node $node): void
    {
        if ($node instanceof Node\Stmt\Namespace_) {
            $this->currentNamespace = $node->name?->toString() ?? '';
        }

        if (
            $node instanceof Node\Stmt\Class_ ||
            $node instanceof Node\Stmt\Interface_ ||
            $node instanceof Node\Stmt\Trait_
        ) {
            $name = ($this->currentNamespace ? $this->currentNamespace . '\\' : '') . $node->name->name;
            $this->declaredInFile[] = ltrim($name, '\\');
        }

        if ($node instanceof Node\Stmt\Class_ && $node->extends) {
            $this->addDependency($node->extends->toString());
        }

        if (($node instanceof Node\Stmt\Class_ || $node instanceof Node\Stmt\Interface_) && $node->implements) {
            foreach ($node->implements as $impl) {
                $this->addDependency($impl->toString());
            }
        }

        if ($node instanceof Node\Stmt\TraitUse) {
            foreach ($node->traits as $trait) {
                $this->addDependency($trait->toString());
            }
        }

        if ($node instanceof Node\AttributeGroup) {
            foreach ($node->attrs as $attr) {
                $this->addDependency($attr->name->toString());
            }
        }

        if ($node->type && $node->type instanceof Node\Name) {
            $this->addDependency($node->type->toString());
        }

        if ($node instanceof Node\Param && $node->type instanceof Node\Name) {
            $this->addDependency($node->type->toString());
        }
    }

    private function addDependency(string $fqn): void
    {
        if (!isset($this->dependsOn[$this->currentFile])) {
            $this->dependsOn[$this->currentFile] = [];
        }

        $fqn = ltrim($fqn, '\\');
        if ($fqn !== 'self' && $fqn !== 'static' && $fqn !== 'parent') {
            $this->dependsOn[$this->currentFile][] = $fqn;
        }
    }

    public function getDependencies(): array
    {
        $result = [];
        foreach ($this->dependsOn as $file => $deps) {
            $result[$file] = $this->declaredInFile;
        }
        return $result;
    }
}
