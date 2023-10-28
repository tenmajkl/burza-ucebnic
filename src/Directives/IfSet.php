<?php

declare(strict_types=1);

namespace App\Directives;

use Lemon\Templating\Exceptions\CompilerException;
use Lemon\Templating\Juice\Compilers\Directives\Directive;
use Lemon\Templating\Juice\Token;

class IfSet implements Directive
{
    public function compileOpenning(Token $token, array $stack): string
    {
        if ('' === $token->content[1]) {
            throw new CompilerException('Directive ifset expects arguments', $token->line);
        }

        return '<?php if (isset('.$token->content[1].')): ?>';
    }

    public function compileClosing(): string
    {
        return '<?php endif ?>';
    }
}
