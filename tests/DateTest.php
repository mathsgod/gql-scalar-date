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
                $email = new Scalar\Date();

                $typeConfig["serialize"] = [$email, "serialize"];
                $typeConfig["parseLiteral"] = [$email, "parseLiteral"];
            }
            return $typeConfig;
        };
        $schema = BuildSchema::build($gql, $typeConfigDecorator);

        //---
        $query = <<<gql
query{
    test(data:"20000102")
}
gql;

        $result = GraphQL::executeQuery($schema, $query);

        $result = $result->toArray();
        print_r($result);
        die();
        $this->assertArrayHasKey("errors", $result);


        //---
        $query = <<<gql
query{
    test(email:"2010-01-02")
}
gql;
        $result = GraphQL::executeQuery($schema, $query);
        $result = $result->toArray();
        $this->assertArrayHasKey("data", $result);
        $this->assertArrayHasKey("test", $result["data"]);
    }
}
