<?php
$rawPostBody = file_get_contents("php://input");
$json = (array) json_decode($rawPostBody);
$courses = json_decode(file_get_contents('./courses.json'), true);
foreach ($json as $index => $payload) {
  $payloadAsArray = json_decode(json_encode($payload), true);
  foreach ($courses["database"] as $idx => $course) {
    if ($course["courseTitle"] == $payloadAsArray["originalCourseTitle"]) {
      $courses["database"][$idx]["courseTitle"] = $payloadAsArray["newCourseTitle"];
    }
  }
}
file_put_contents('./courses.json', json_encode($courses, JSON_PRETTY_PRINT));
header('Location: index.php');
