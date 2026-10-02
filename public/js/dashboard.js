$(function () {
  var start = moment().subtract(6, "days");
  var end = moment();

  function cb(start, end) {
    let dtSay = start.format("D MMM YYYY") + " - " + end.format("D MMM YYYY");
    if (start.format("MM/DD/YYYY") == end.format("MM/DD/YYYY")) {
      dtSay = start.format("D MMMM YYYY");
    }else if (start.format("MM/YYYY") == end.format("MM/YYYY")) {
      dtSay = start.format("D") + " - " + end.format("D MMMM YYYY");
    }else if (start.format("YYYY") == end.format("YYYY")) {
      dtSay = start.format("D MMM") + " - " + end.format("D MMM YYYY");
    }

    $("#reportrange span").html(
      dtSay,
    );
    $.ajax({
      url: "/api/dashboard",
      type: "POST",
      data: {
        start: start.format("YYYY-MM-DD"),
        end: end.format("YYYY-MM-DD")
      },
      success: function(resp) {
        setPackage(resp.package);
        setPkgCities(resp.pkgcity);
        setPkgSPG(resp.pkgspg);
      }
    });
  }

  $("#reportrange").daterangepicker(
    {
      minDate:"03/15/2023",
      maxDate:moment(),
      startDate: start,
      endDate: end,
      ranges: {
        "Today": [moment(), moment()],
        "Yesterday": [
          moment().subtract(1, "days"),
          moment().subtract(1, "days"),
        ],
        "Last 7 days": [moment().subtract(6, "days"), moment()],
        "Last 30 days": [moment().subtract(29, "days"), moment()],
        "This month": [
          moment().startOf("month"),
          moment().endOf("month"),
        ],
        "Last month": [
          moment().subtract(1, "month").startOf("month"),
          moment().subtract(1, "month").endOf("month"),
        ],
      },
    },
    cb
  );

  cb(start, end);
});

function setPackage(data) {
  pkgChart.data.labels = [];
  pkgChart.data.datasets[0].data = [];
  pkgChart.data.datasets[1].data = [];
  pkgChart.data.datasets[2].data = [];
  if (agent.search(/mobile/i) > 0) {
    pkgChart.options.aspectRatio = 1/1.5;
  }else{
    pkgChart.options.aspectRatio = 2.1/1;
  }
  pkgChart.options.scales.x.stacked = true;
  pkgChart.options.scales.y.stacked = true;

  data.forEach(function(v,i){
    pkgChart.data.labels.push(v.x);
    pkgChart.data.datasets[0].data.push(v.satu);
    pkgChart.data.datasets[1].data.push(v.pkt6);
    pkgChart.data.datasets[2].data.push(v.total);
  });

  if (data.length > 7) {
    if (agent.search(/mobile/i) > 0) {
      pkgChart.options.aspectRatio = 1/4;
    }
  }else{
    if (data.length == 1) {
      pkgChart.options.scales.x.stacked = false;
      pkgChart.options.scales.y.stacked = false;
    }
  }
  
  pkgChart.update();
  pkgChart.resize();
}

function setPkgCities(data){
  cityChart.data.labels = [];
  cityChart.data.datasets[0].data = [];
  cityChart.data.datasets[1].data = [];
  cityChart.data.datasets[2].data = [];
  if (agent.search(/mobile/i) > 0) {
    cityChart.options.aspectRatio = 1/1.5;
  }else{
    cityChart.options.aspectRatio = 2.1/1;
  }
  cityChart.options.scales.x.stacked = true;
  cityChart.options.scales.y.stacked = true;

  data.forEach(function(v,i){
    cityChart.data.labels.push(v.x);
    cityChart.data.datasets[0].data.push(v.satu);
    cityChart.data.datasets[1].data.push(v.pkt6);
    cityChart.data.datasets[2].data.push(v.total);
  });

  if (data.length > 7) {
    if (agent.search(/mobile/i) > 0) {
      cityChart.options.aspectRatio = 1/2;
    }
  }else{
    if (data.length == 1) {
      cityChart.options.scales.x.stacked = false;
      cityChart.options.scales.y.stacked = false;
    }
  }
  
  cityChart.update();
  cityChart.resize();
}

function setPkgSPG(data){
  $("#bbSPG").find(".card-body").text("");
  data.forEach(function(v){
    let spg = '<div class="row mb-4"><div class="col pe-0"><div class="d-flex align-items-center flex-shrink-0 mr-3"><div class="avatar avatar-xl mr-3 bg-gray-200"><img class="avatar-img img-fluid" src="/profiles/' + 
      v.avatar + '" alt="' + v.name + '" /></div><div class="ps-2 d-flex flex-column font-weight-bold"><span class="text-dark line-height-normal mb-1">' + 
      v.name + '</span><div class="small text-muted line-height-normal text-truncate">' + v.city + '</div></div></div></div><div class="col-auto d-flex align-items-center"><div class="small">' + 
      number_format(v.total,0,",",".") + '</div></div></div>';
    $("#bbSPG").find(".card-body").append(spg);
  });
}

function number_format(number, decimals, dec_point, thousands_sep) {
  number = (number + "").replace(",", "").replace(" ", "");
  var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = typeof thousands_sep === "undefined" ? "," : thousands_sep,
    dec = typeof dec_point === "undefined" ? "." : dec_point,
    s = "",
    toFixedFix = function(n, prec) {
        var k = Math.pow(10, prec);
        return "" + Math.round(n * k) / k;
    };
  s = (prec ? toFixedFix(n, prec) : "" + Math.round(n)).split(".");
  if (s[0].length > 3) {
      s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
  }
  if ((s[1] || "").length < prec) {
      s[1] = s[1] || "";
      s[1] += new Array(prec - s[1].length + 1).join("0");
  }
  return s.join(dec);
}