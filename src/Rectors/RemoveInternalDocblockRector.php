<?php

declare(strict_types=1);

namespace ArtisanBuild\Turbulence\Rectors;

use PhpParser\Comment\Doc;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use Rector\Rector\AbstractRector;

final class RemoveInternalDocblockRector extends AbstractRector
{
    public function getNodeTypes(): array
    {
        return [Class_::class];
    }

    public function refactor(Node $node): ?Node
    {
        if (! $node instanceof Class_) {
            return null;
        }

        $docComment = $node->getDocComment();

        if ($docComment === null) {
            return null;
        }

        $docText = $docComment->getText();

        if (str_contains($docText, '@internal')) {
            $node->setDocComment(new Doc(str_replace('@internal', '', $docText)));
        }

        return $node;
    }
}
