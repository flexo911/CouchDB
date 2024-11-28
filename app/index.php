<?php

use common\Curl_Request;

include_once __DIR__ . '/common/Curl_Request.php';


echo 'Create DB Response: <br />';

$Curl_Request = new Curl_Request();
$dbName = "my_database";
echo '<pre>';
var_dump($Curl_Request->put('my_database'));
echo '</pre>';

// Створення документів
$documents = [
    ["name" => "Alice", "age" => 25, "hobbies" => ["reading", "cycling"]],
    ["name" => "Bob", "age" => 30, "address" => ["city" => "Kyiv", "country" => "Ukraine"]],
    ["name" => "Charlie", "scores" => [95, 87, 93]],
    ["status" => "active", "created_at" => "2024-11-17"],
    ["title" => "Document 5", "content" => "This is a test document."]
];

echo 'Insert Documents: <br />';
echo '<pre>';
foreach ($documents as $doc) {
    var_dump($Curl_Request->post($dbName, $doc));
}
echo '</pre>';

echo '<pre>';
echo 'Filter request: <br />';
$filterQuery = [
    "selector" => [
        "age" => ['$gt' => 20]
    ]
];
$filter = $Curl_Request->post($dbName, $filterQuery);

print_r($filter );
echo '</pre>';


echo 'Map function1: <br />';

    $mapFunction = "function(doc) { return doc; }";
    $designDocument = [
        "_id" => "_design/example",
        "views" => [
            "by_age" => ["map" => $mapFunction]
        ]
    ];

$map = $Curl_Request->put($dbName.'/_design/example', $designDocument);
echo '<pre>';
print_r($map );
echo '<pre/>';


echo 'Map function2: <br />';

$mapFunctionAggregate = "
    function(doc) {
        if (doc.age) emit(doc.age, 1);
    }
";

$reduceFunction = "
    function(keys, values, rereduce) {
        return sum(values);
    }
";

$designDocumentAggregate = [
    "_id" => "_design/example",
    "views" => [
        "age_count" => [
            "map" => $mapFunctionAggregate,
            "reduce" => $reduceFunction
        ]
    ]
];

$map = $Curl_Request->put($dbName.'/_design/example', $designDocumentAggregate );
echo '<pre>';
print_r($map );
echo '<pre/>';

echo 'Replicate Database: <br />';

echo '<pre>';
var_dump($Curl_Request->put('my_database_copy'));
echo '</pre>';
$db_url = $Curl_Request->db_url;
$replicationData = ["source" => "http://admin:password@127.0.0.1:80/couchdb/my_database", "target" => $db_url."http://admin:password@127.0.0.1:80/couchdb/my_database_copy"];
echo '<pre>';
$replication = $Curl_Request->post('_replicate', $replicationData  );

print_r( $replication );
echo '<pre/>';

