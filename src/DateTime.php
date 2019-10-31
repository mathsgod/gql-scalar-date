<?

namespace Scalar;

use GraphQL\Error\Error;
use GraphQL\Utils\Utils;
use GraphQL\Language\AST\StringValueNode;

class DateTime
{
    public $description = "datetime";

    public function serialize($value)
    {
        $d = \DateTime::createFromFormat("Y-m-d H:i:s", $value);
        if ($d && $d->format("Y-m-d H:i:s") == $value) {
            return $value;
        }
        throw new Error("Cannot represent following value as datetime: " . Utils::printSafeJson($value));
    }

    public function parseLiteral($valueNode, array $variables = null)
    {
        // Note: throwing GraphQL\Error\Error vs \UnexpectedValueException to benefit from GraphQL
        // error location in query:
        if (!$valueNode instanceof StringValueNode) {
            throw new Error('Query error: Can only parse strings got: ' . $valueNode->kind, [$valueNode]);
        }

        $d = \DateTime::createFromFormat("Y-m-d H:i:s", $valueNode->value);
        if ($d && $d->format("Y-m-d H:i:s") == $valueNode->value) {
            return $valueNode->value;
        }

        throw new Error("Not a valid datetime", [$valueNode]);
    }
}
