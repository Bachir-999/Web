$(document).ready(function () {

  // إضافة مادة
  $("#addCourse").click(function () {
    var row = $(".course-row").first().clone();
    row.find("input").val("");
    $("#courses").append(row);
  });

  // حساب المعدل (AJAX)
  $("#gpaForm").submit(function (e) {
    e.preventDefault();

    $.ajax({
      url: "calculate.php",
      type: "POST",
      data: $(this).serialize(),
      dataType: "json",

      success: function (res) {

        let color = "alert-info";

        if (res.gpa >= 3.7) color = "alert-success";
        else if (res.gpa >= 2.0) color = "alert-warning";
        else color = "alert-danger";

        $("#result").html(
          '<div class="alert ' + color + '">' + res.message + '</div>' +
          res.tableHtml
        );
      },

      error: function () {
        $("#result").html('<div class="alert alert-danger">خطأ في السيرفر</div>');
      }

    });

  });

});