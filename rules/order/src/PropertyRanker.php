<?php

declare(strict_types=1);

namespace Rector\Order;

use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\Property;
use PHPStan\Type\ArrayType;
use PHPStan\Type\BooleanType;
use PHPStan\Type\CallableType;
use PHPStan\Type\FloatType;
use PHPStan\Type\IntegerType;
use PHPStan\Type\IntersectionType;
use PHPStan\Type\IterableType;
use PHPStan\Type\StringType;
use PHPStan\Type\TypeWithClassName;
use PHPStan\Type\UnionType;
use Rector\BetterPhpDocParser\PhpDocInfo\PhpDocInfo;
use Rector\Core\Exception\NotImplementedException;
use Rector\NodeTypeResolver\Node\AttributeKey;

final class PropertyRanker
{
    /**
     * @param Property|ClassConst $stmt
     */
    public function rank(Stmt $stmt): int
    {
        /** @var PhpDocInfo|null $phpDocInfo */
        $phpDocInfo = $stmt->getAttribute(AttributeKey::PHP_DOC_INFO);
        if ($phpDocInfo === null) {
            return 1;
        }

        switch (true) {
            case $stmt->isPublic():
                $decreaseRankBy = 2;
                break;
            case $stmt->isProtected():
                $decreaseRankBy = 1;
                break;
            default:
                $decreaseRankBy = 0;
        }

        $varType = $phpDocInfo->getVarType();
        if ($stmt instanceof ClassConst) {
            return 5 - $decreaseRankBy;
        }

        if ($varType instanceof StringType || $varType instanceof IntegerType || $varType instanceof BooleanType || $varType instanceof FloatType) {
            return 10 - $decreaseRankBy;
        }

        if ($varType instanceof ArrayType || $varType instanceof IterableType) {
            return 15 - $decreaseRankBy;
        }

        if ($varType instanceof TypeWithClassName) {
            return 20 - $decreaseRankBy;
        }

        if ($varType instanceof IntersectionType) {
            return 25 - $decreaseRankBy;
        }

        if ($varType instanceof UnionType) {
            return 30 - $decreaseRankBy;
        }

        if ($varType instanceof CallableType) {
            return 35 - $decreaseRankBy;
        }

        throw new NotImplementedException(get_class($varType));
    }
}
