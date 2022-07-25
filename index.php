<?php
$courses = json_decode(file_get_contents('./courses.json'), true);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Course Manager</title>
  <link rel="stylesheet" href="https://cdn.simplecss.org/simple.css" type="text/css">
</head>

<body>
  <div>
    <form action="newcourse.php" method="post">
      <input type="text" name="courseName" placeholder="Enter your course">
      <button>ADD</button>
    </form>
    <br>
    <?php foreach ($courses["database"] as $index => $course) : ?>
      <div style="margin-bottom: 20px;">

        <form style="display: inline" action="change_status.php" method="post">
          <input type="hidden" name="courseName" value="<?= $course["courseTitle"] ?>">
          <input type="checkbox" name="status" value="1" <?= $course['completed'] ? 'checked' : '' ?>>
        </form>

        <span class="courseTitle" data-originalcoursename="<?= $course["courseTitle"] ?>" contentEditable="true">
          <?php echo $course["courseTitle"]  ?></span>

        <form style="display: inline" action="delete.php" method="post">
          <input type="hidden" name="courseName" value="<?= $course["courseTitle"] ?>">
          <button>Delete</button>
        </form>

      </div>
    <?php endforeach; ?>

    <form style="display: inline" id="updateForm" action="updateCourse.php" method="post">
      <input type="hidden" name="courseName" value="<?= $course["courseTitle"] ?>">
      <button ng-show="trunOn" id="updateButton">Update</button>
    </form>
  </div>

  <script>
    const checkboxes = document.querySelectorAll('input[type=checkbox]');
    checkboxes.forEach(ch => {
      ch.onclick = function() {
        this.parentNode.submit()
      };
    })
    const editedCourses = [];
    const editableCourseTitles = document.querySelectorAll('.courseTitle');
    const updateButton = document.querySelector('#updateButton');

    editableCourseTitles.forEach(course => course.addEventListener("blur", (e) => {
      const updateForm = document.querySelector('#updateForm');
      updateForm.style.display = "block";
      editedCourses.push({
        "originalCourseTitle": e.target.getAttribute("data-originalcoursename"),
        "newCourseTitle": e.target.innerText
      });
    }));

    updateButton.addEventListener("click", async () => {
      const response = await fetch('/updateCourse.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(editedCourses)
      });
    });
  </script>
</body>

</html>
