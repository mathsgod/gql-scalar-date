# gql-scalar-email

```php
    
    $gql = "
scalar Date
scalar DateTime
type Query{
    testDate(d:Date):Date
    testDateTime(dt:DateTime):DateTime
}      
";

   $typeConfigDecorator = function ($typeConfig) {
        $name = $typeConfig['name'];

        if ($name === 'Date') {
            $email = new Scalar\Date();

            $typeConfig["serialize"] = [$email, "serialize"];
            $typeConfig["parseLiteral"] = [$email, "parseLiteral"];
        }

            if ($name === 'DateTime') {
            $email = new Scalar\DateTime();

            $typeConfig["serialize"] = [$email, "serialize"];
            $typeConfig["parseLiteral"] = [$email, "parseLiteral"];
        }

        return $typeConfig;
    };

    $schema = BuildSchema::build($gql, $typeConfigDecorator);
```
