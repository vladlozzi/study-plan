<?php
ini_set("error_reporting",E_ALL); ini_set("display_errors",1); ini_set("display_startup_errors",1);
define("IN_ADMIN", TRUE); require "../dbu.php"; mb_internal_encoding("UTF-8");
function insert_into_raw($conn, $acadGroup, $semester, $teacher, $department) {
      $isInRawQuery = "
      SELECT * FROM `acadGroupTeacherRaw`
      WHERE `acadGroup` = \"$acadGroup\" AND `semester` = $semester AND
            `teacher` = \"$teacher\" AND `department` = \"$department\"
      ";
      $isInRawResult = mysqli_query($conn, $isInRawQuery) or die($isInRawQuery . " : ".mysqli_error($conn));
      if (mysqli_num_rows($isInRawResult) == 0) {
        $insertAcadGroupTeacherRawQuery = "
        INSERT INTO `acadGroupTeacherRaw`VALUES(\"$acadGroup\", $semester, \"$teacher\", \"$department\")
        ";
        $insertAcadGroupTeacherRawResult = mysqli_query($conn, $insertAcadGroupTeacherRawQuery) or
          die($insertAcadGroupTeacherRawQuery . " : ".mysqli_error($conn));
      }
}

echo str_replace("Kiev","Kyiv",date("Y-m-d H:i:s (e P)")) . " Старт<br>";

$acadGroupTeacherRawQuery = "TRUNCATE `acadGroupTeacherRaw`";
$acadGroupTeacherRawResult = mysqli_query($conn, $acadGroupTeacherRawQuery) or
   die($acadGroupTeacherRawQuery . " : ".mysqli_error($conn));
$fullTimeGroupsQuery = "
SELECT a.`id`, b.`fakultet_shufr`, a.`nazva_grupu`
FROM `catalogGroup` a, `catalogFakultet` b, `catalogWorkEduPlan` c
WHERE a.`shufr_fak` = b.`id` AND a.`plan_id` = c.id
  AND NOT (a.`nazva_grupu` LIKE BINARY '%з%') AND c.`reg_number` LIKE BINARY '%.Д'
ORDER BY b.`fakultet_shufr`, a.`num_kurs`, a.`nazva_grupu`
";
$fullTimeGroupsResult = mysqli_query($conn, $fullTimeGroupsQuery) or die($fullTimeGroupsQuery . " : ".mysqli_error($conn));
while ($fullTimeGroupsRow = mysqli_fetch_array($fullTimeGroupsResult)) {
  $fullTimeGroup = $fullTimeGroupsRow['nazva_grupu'];

  // Пошук академгрупи окремо, в лекційних потоках та збірних і закріплених за нею викладачів

  $fullTimeGroupInAcadWorkGroupQuery = "
  SELECT *
  FROM `acadWorkGroupTeacher`
  WHERE `group` = '$fullTimeGroup' OR
        `lectureStream` LIKE BINARY '%$fullTimeGroup,%' OR
        `collectiveGroup` LIKE BINARY '%$fullTimeGroup,%'
  "; // echo $fullTimeGroupInAcadWorkGroup . "<br>";
  $fullTimeGroupInAcadWorkGroupResult = mysqli_query($conn, $fullTimeGroupInAcadWorkGroupQuery) or
    die($fullTimeGroupInAcadWorkGroupQuery . " : ".mysqli_error($conn));
  while ($fullTimeGroupInAcadWorkGroupRow = mysqli_fetch_array($fullTimeGroupInAcadWorkGroupResult)) {
    $semester = $fullTimeGroupInAcadWorkGroupRow['semester'];
    $department = $fullTimeGroupInAcadWorkGroupRow['department'];
    $lectures = $fullTimeGroupInAcadWorkGroupRow['lectures'];
    if (mb_strlen($lectures) > 3) {
      insert_into_raw($conn, $fullTimeGroup, $semester, $lectures, $department);
    }
    $practicals = $fullTimeGroupInAcadWorkGroupRow['practicals'];
    if (mb_strlen($practicals) > 3) {
      insert_into_raw($conn, $fullTimeGroup, $semester, $practicals, $department);
    }
    $laboratories = $fullTimeGroupInAcadWorkGroupRow['laboratories'];
    if (mb_strlen($laboratories) > 3) {
      insert_into_raw($conn, $fullTimeGroup, $semester, $laboratories, $department);
    }
    $individualConsults = $fullTimeGroupInAcadWorkGroupRow['individualConsults'];
    if (mb_strlen($individualConsults) > 3) {
      insert_into_raw($conn, $fullTimeGroup, $semester, $individualConsults, $department);
    }
    $groupConsults = $fullTimeGroupInAcadWorkGroupRow['groupConsults'];
    if (mb_strlen($groupConsults) > 3) {
      insert_into_raw($conn, $fullTimeGroup, $semester, $groupConsults, $department);
    }
    $exams = $fullTimeGroupInAcadWorkGroupRow['exams'];
    if (mb_strlen($exams) > 3) {
      insert_into_raw($conn, $fullTimeGroup, $semester, $exams, $department);
    }
    $examConsults = $fullTimeGroupInAcadWorkGroupRow['examConsults'];
    if (mb_strlen($examConsults) > 3) {
      insert_into_raw($conn, $fullTimeGroup, $semester, $examConsults, $department);
    }
    $passes = $fullTimeGroupInAcadWorkGroupRow['passes'];
    if (mb_strlen($passes) > 3) {
      insert_into_raw($conn, $fullTimeGroup, $semester, $passes, $department);
    }
    $individualTasks = $fullTimeGroupInAcadWorkGroupRow['individualTasks'];
    if (mb_strlen($individualTasks) > 3) {
      insert_into_raw($conn, $fullTimeGroup, $semester, $individualTasks, $department);
    }
    $diplomaPapers = $fullTimeGroupInAcadWorkGroupRow['diplomaPapers'];
    if (mb_strlen($diplomaPapers) > 3) {
      insert_into_raw($conn, $fullTimeGroup, $semester, $diplomaPapers, $department);
    }
    $direction = $fullTimeGroupInAcadWorkGroupRow['direction'];
    if (mb_strlen($direction) > 3) {
      insert_into_raw($conn, $fullTimeGroup, $semester, $direction, $department);
    }
    $examCommission = $fullTimeGroupInAcadWorkGroupRow['examCommission'];
    if (mb_strlen($examCommission) > 3) {
      insert_into_raw($conn, $fullTimeGroup, $semester, $examCommission, $department);
    }
  }
}

echo str_replace("Kiev","Kyiv",date("Y-m-d H:i:s (e P)")) . " Готово";