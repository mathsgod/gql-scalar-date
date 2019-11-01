<?
declare(strict_types=1);
error_reporting(E_ALL && ~E_WARNING);

use GraphQL\Utils\BuildSchema;
use GraphQL\GraphQL;
use PHPUnit\Framework\TestCase;

final class DateTest extends TestCase
{

    public function test()
    {
        $gql = "
scalar Date
type Query{
    test(date:Date):Date
}      
";
        $typeConfigDecorator = function ($typeConfig) {
            $name = $typeConfig['name'];
            if ($name === 'Date') {


                $scalar = new Scalar\Date();
                $typeConfig["serialize"] = [$scalar, "serialize"];
                $typeConfig["parseLiteral"] = [$scalar, "parseLiteral"];
            }
            return $typeConfig;
        };
        $schema = BuildSchema::build($gql, $typeConfigDecorator);

        //---
        $query = <<<gql
query{
    test(date:"20000102")
}
gql;

        $result = GraphQL::executeQuery($schema, $query);

        $result = $result->toArray();
        $this->assertArrayHasKey("errors", $result);


        //---
        $query = <<<gql
query{
    test(date:"2010-01-02")
}
gql;
        $result = GraphQL::executeQuery($schema, $query);
        $result = $result->toArray();
        $this->assertArrayHasKey("data", $result);
        $this->assertArrayHasKey("test", $result["data"]);
    }
}
