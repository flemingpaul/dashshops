function getData(url, id1) {
  //alert(url);
  $.get(url, function (data) {
    $("#" + id1).html(data);
  });
  //is_processing = false;
}

function getData2(url, id1, func, arg) {
  //alert(url);
  $.get(url, function (data) {
    $("#" + id1).html(data);
    if (typeof arg === "undefined") {
      func();
    } else {
      func(arg);
    }
  });
  //is_processing = false;
}
