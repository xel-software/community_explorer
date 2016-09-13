<?php

namespace ElasticBundle\DoctrineFunctions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode,
    Doctrine\ORM\Query\Lexer;

class InetAton extends FunctionNode
{
    private $arithmeticExpression;

    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {

        $lexer = $parser->getLexer();

        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);

        $this->arithmeticExpression = $parser->SimpleArithmeticExpression();

        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return 'INET_ATON(' . $sqlWalker->walkSimpleArithmeticExpression($this->arithmeticExpression) . ')';
    }
}