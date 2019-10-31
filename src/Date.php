<?

namespace Scalar;

use GraphQL\Error\Error;
use GraphQL\Utils\Utils;
use GraphQL\Language\AST\StringValueNode;

class Date
{
    public $description = "date";

    public function serialize($value)
    {
        $d = \DateTime::createFromFormat("Y-m-d", $value);
        if ($d && $d->format("Y-m-d") == $value) {
            return $value;
        }
        throw new Error("Cannot represent following value as date: " . Utils::printSafeJson($value));
    }

    public function parseLiteral($valueNode, array $variables = null)
    {
        // Note: throwing GraphQL\Error\Error vs \UnexpectedValueException to benefit from GraphQL
        // error location in query:
        if (!$valueNode instanceof StringValueNode) {
            throw new Error('Query error: Can only parse strings got: ' . $valueNode->kind, [$valueNode]);
        }

 
        $d = \DateTime::createFromFormat("Y-m-d", $valueNode->value);
        if ($d && ($d->format("Y-m-d") == $valueNode->value)) {
            return $valueNode->value;
        }

        throw new Error("Not a valid date", [$valueNode]);
    }
}
