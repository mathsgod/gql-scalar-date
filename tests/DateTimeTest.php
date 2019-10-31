<?
declare(strict_types=1);
error_reporting(E_ALL && ~E_WARNING);

use GraphQL\Utils\BuildSchema;
use GraphQL\GraphQL;
use PHPUnit\Framework\TestCase;

final class DateTimeTest extends TestCase
{

    public function test()
    {
        $gql = "
scalar DateTime
type Query{
    test(datetime:DateTime):DateTime
}      
";
        $typeConfigDecorator = function ($typeConfig) {
            $name = $typeConfig['name'];

            if ($name === 'DateTime') {
                $email = new Scalar\DateTime();

                $typeConfig["serialize"] = [$email, "serialize"];
                $typeConfig["parseLiteral"] = [$email, "parseLiteral"];
            }
            return $typeConfig;
        };
        $schema = BuildSchema::build($gql, $typeConfigDecorator);

        //---
        $query = <<<gql
query{
    test(datetime:"20000102")
}
gql;

        $result = GraphQL::executeQuery($schema, $query);

        $result = $result->toArray();
        $this->assertArrayHasKey("errors", $result);


        //---
        $query = <<<gql
query{
    test(datetime:"2010-01-02 12:05:20")
}
gql;
        $result = GraphQL::executeQuery($schema, $query);
        $result = $result->toArray();
        $this->assertArrayHasKey("data", $result);
        $this->assertArrayHasKey("test", $result["data"]);
    }
}
