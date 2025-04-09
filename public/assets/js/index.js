"use strict";
var options1 = {
  series: [
    {
      type: "area",
      data: [20, 65, 38, 72, 55, 80, 65, 76, 55, 80, 40, 80],
    },
    {
      type: "line",
      data: [85, 45, 75, 50, 85, 43, 80, 40, 70, 64, 50, 89],
    },
  ],
  chart: {
    animations: {
      enabled: false,
    },
    height: 150,
    width: "500px",
    type: "line",
    toolbar: {
      show: false,
    },
  },
  stroke: {
    width: [1, 2],
    curve: "straight",
    dashArray: [0, 5],
  },
  colors: ["#5a66f1", "#d1dae5"],
  grid: {
    borderColor: "transparent",
  },
  fill: {
    colors: undefined,
    opacity: [0.1, 1],
    type: ["gradient", "solid"],
    gradient: {
      shade: "light",
      type: "vertical",
      shadeIntensity: 0.5,
      gradientToColors: undefined,
      inverseColors: true,
      opacityFrom: 0.5,
      opacityTo: 0,
      stops: [0, 80, 100],
      colorStops: [],
    },
  },
  labels: {
    enabled: false,
  },
  markers: {
    size: 0,
  },
  xaxis: {
    show: false,
    labels: {
      show: false,
    },
  },
  yaxis: {
    show: false,
    labels: {
      show: false,
    },
  },
  tooltip: {
    show: false,
  },
};
var spark1 = new ApexCharts(document.querySelector("#sales-chart"), options1);
spark1.render();

var options2 = {
  series: [
    {
      type: "area",
      data: [20, 65, 38, 72, 55, 80, 65, 76, 55, 80, 40, 80],
    },
    {
      type: "line",
      data: [85, 45, 75, 50, 85, 43, 80, 40, 70, 64, 50, 89],
    },
  ],
  chart: {
    animations: {
      enabled: false,
    },
    height: 150,
    width: "500px",
    type: "line",
    toolbar: {
      show: false,
    },
  },
  stroke: {
    width: [1, 2],
    curve: "straight",
    dashArray: [0, 5],
  },
  colors: ["#5a66f1", "#d1dae5"],
  grid: {
    borderColor: "transparent",
  },
  fill: {
    colors: undefined,
    opacity: [0.1, 1],
    type: ["gradient", "solid"],
    gradient: {
      shade: "light",
      type: "vertical",
      shadeIntensity: 0.5,
      gradientToColors: undefined,
      inverseColors: true,
      opacityFrom: 0.5,
      opacityTo: 0,
      stops: [0, 80, 100],
      colorStops: [],
    },
  },
  labels: {
    enabled: false,
  },
  markers: {
    size: 0,
  },
  xaxis: {
    show: false,
    labels: {
      show: false,
    },
  },
  yaxis: {
    show: false,
    labels: {
      show: false,
    },
  },
  tooltip: {
    show: false,
  },
};
document.getElementById("sales-chart2").innerHTML = "";
var spark2 = new ApexCharts(document.querySelector("#sales-chart2"), options2);
spark2.render();

var options3 = {
  series: [
    {
      type: "area",
      data: [20, 65, 38, 72, 55, 80, 65, 76, 55, 80, 40, 80],
    },
    {
      type: "line",
      data: [85, 45, 75, 50, 85, 43, 80, 40, 70, 64, 50, 89],
    },
  ],
  chart: {
    animations: {
      enabled: false,
    },
    height: 150,
    width: "500px",
    type: "line",
    toolbar: {
      show: false,
    },
  },
  stroke: {
    width: [1, 2],
    curve: "straight",
    dashArray: [0, 5],
  },
  colors: ["#5a66f1", "#d1dae5"],
  grid: {
    borderColor: "transparent",
  },
  fill: {
    colors: undefined,
    opacity: [0.1, 1],
    type: ["gradient", "solid"],
    gradient: {
      shade: "light",
      type: "vertical",
      shadeIntensity: 0.5,
      gradientToColors: undefined,
      inverseColors: true,
      opacityFrom: 0.5,
      opacityTo: 0,
      stops: [0, 80, 100],
      colorStops: [],
    },
  },
  labels: {
    enabled: false,
  },
  markers: {
    size: 0,
  },
  xaxis: {
    show: false,
    labels: {
      show: false,
    },
  },
  yaxis: {
    show: false,
    labels: {
      show: false,
    },
  },
  tooltip: {
    show: false,
  },
};
document.getElementById("sales-chart3").innerHTML = "";
var spark3 = new ApexCharts(document.querySelector("#sales-chart3"), options3);
spark3.render();

var options4 = {
  series: [
    {
      type: "area",
      data: [20, 65, 38, 72, 55, 80, 65, 76, 55, 80, 40, 80],
    },
    {
      type: "line",
      data: [85, 45, 75, 50, 85, 43, 80, 40, 70, 64, 50, 89],
    },
  ],
  chart: {
    animations: {
      enabled: false,
    },
    height: 150,
    width: "500px",
    type: "line",
    toolbar: {
      show: false,
    },
  },
  stroke: {
    width: [1, 2],
    curve: "straight",
    dashArray: [0, 5],
  },
  colors: ["#5a66f1", "#d1dae5"],
  grid: {
    borderColor: "transparent",
  },
  fill: {
    colors: undefined,
    opacity: [0.1, 1],
    type: ["gradient", "solid"],
    gradient: {
      shade: "light",
      type: "vertical",
      shadeIntensity: 0.5,
      gradientToColors: undefined,
      inverseColors: true,
      opacityFrom: 0.5,
      opacityTo: 0,
      stops: [0, 80, 100],
      colorStops: [],
    },
  },
  labels: {
    enabled: false,
  },
  markers: {
    size: 0,
  },
  xaxis: {
    show: false,
    labels: {
      show: false,
    },
  },
  yaxis: {
    show: false,
    labels: {
      show: false,
    },
  },
  tooltip: {
    show: false,
  },
};
document.getElementById("sales-chart4").innerHTML = "";
var spark4 = new ApexCharts(document.querySelector("#sales-chart4"), options4);
spark4.render();

/*  sales overview chart */
var options = {
  series: [
    {
      name: "Income",
      type: "line",
      data: [20, 38, 38, 72, 55, 63, 43, 76, 55, 80, 40, 80],
    },
    {
      name: "Expenses",
      type: "line",
      data: [85, 65, 75, 38, 85, 35, 62, 40, 40, 64, 50, 89],
    },
  ],
  chart: {
    animations: {
      enabled: false,
    },
    height: 300,
    type: "line",
    zoom: {
      enabled: false,
    },
    toolbar: {
      show: false,
    },
  },
  dataLabels: {
    enabled: false,
  },
  legend: {
    show: false,
  },
  stroke: {
    curve: "straight",
    width: "3",
    dashArray: [0, 5],
  },
  grid: {
    borderColor: "rgba(107 ,114 ,128,0.1)",
  },
  colors: ["rgb(90,102,241)", "rgb(203,213,225)"],
  yaxis: {
    title: {
      style: {
        color: "#adb5be",
        fontSize: "14px",
        fontFamily: "Inter, sans-serif",
        fontWeight: 600,
        cssClass: "apexcharts-yaxis-label",
      },
    },
    labels: {
      style: {
        colors: "rgb(107 ,114 ,128)",
        fontSize: "12px",
      },
      formatter: function (y) {
        return y.toFixed(0) + "";
      },
    },
  },
  xaxis: {
    type: "month",
    categories: [
      "Jan",
      "Feb",
      "Mar",
      "Apr",
      "May",
      "Jun",
      "Jul",
      "Aug",
      "Sep",
      "Oct",
      "Nov",
      "Dec",
    ],
    axisBorder: {
      show: true,
      color: "rgba(119, 119, 142, 0.05)",
      offsetX: 0,
      offsetY: 0,
    },
    axisTicks: {
      show: true,
      borderType: "solid",
      color: "rgba(119, 119, 142, 0.05)",
      width: 6,
      offsetX: 0,
      offsetY: 0,
    },
    labels: {
      rotate: -90,
      style: {
        colors: "rgb(107 ,114 ,128)",
        fontSize: "12px",
      },
    },
  },
};
var chart = new ApexCharts(document.querySelector("#salesOverview"), options);
chart.render();

/* basic chart */
var options = {
  series: [
    {
      data: [400, 470, 540, 690, 1100, 1380],
    },
  ],
  chart: {
    type: "bar",
    height: 370,
    toolbar: {
      show: false,
    },
  },
  fill: {
    type: "gradient",
    gradient: {
      shadeIntensity: 1,
      opacityFrom: 0.7,
      opacityTo: 1,
      colorStops: [
        {
          offset: 0,
          color: "#60a5fa",
          opacity: 1,
        },
        {
          offset: 100,
          color: "#5a66f1",
          opacity: 1,
        },
      ],
    },
  },
  plotOptions: {
    bar: {
      borderRadius: 4,
      horizontal: true,
    },
  },
  colors: ["#5e76a6"],
  grid: {
    borderColor: "transparent",
  },
  dataLabels: {
    enabled: false,
  },
  xaxis: {
    categories: [
      "Facebook",
      "Instagram",
      "Dribble",
      "Twitter",
      "Chrome",
      "Pininterest",
    ],
    labels: {
      show: true,
      style: {
        colors: "#adb5be",
        fontSize: "11px",
        fontWeight: 600,
        cssClass: "apexcharts-xaxis-label",
      },
    },
  },
  yaxis: {
    labels: {
      show: true,
      style: {
        colors: "#adb5be",
        fontSize: "11px",
        fontWeight: 600,
        cssClass: "apexcharts-yaxis-label",
      },
    },
  },
};
var chart1 = new ApexCharts(document.querySelector("#visitors"), options);
chart1.render();

/* Chartjs (#donut)  */
var myCanvas = document.getElementById("sales-donut");
var myCanvasContext = myCanvas.getContext("2d");
var myChart;
myChart = new Chart(myCanvas, {
  type: "doughnut",
  data: {
    labels: ["Items", "Revenue"],
    datasets: [
      {
        data: [60, 40],
        backgroundColor: ["rgb(90, 102, 241)", "rgb(96, 165, 250)"],
        borderWidth: 0,
      },
    ],
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
      legend: {
        display: false,
      },
    },
    cutout: 90,
  },
});
/* Chartjs (#donut) closed */

/*  sales overview chart */
function salesOverview() {
  chart.updateOptions({
    colors: ["rgb(" + myVarVal + ")", "rgb(203,213,225)"],
  });
}
function sparkchart() {
  function rgbToHex(r, g, b) {
    return "#" + ((1 << 24) | (r << 16) | (g << 8) | b).toString(16).slice(1);
  }
  spark1.updateOptions({
    colors: [
      rgbToHex(
        myVarVal.split(",")[0],
        myVarVal.split(",")[1],
        myVarVal.split(",")[2]
      ),
      "rgb(203,213,225)",
    ],
  });
}
function sparkchart2() {
  function rgbToHex(r, g, b) {
    return "#" + ((1 << 24) | (r << 16) | (g << 8) | b).toString(16).slice(1);
  }
  spark2.updateOptions({
    colors: [
      rgbToHex(
        myVarVal.split(",")[0],
        myVarVal.split(",")[1],
        myVarVal.split(",")[2]
      ),
      "rgb(203,213,225)",
    ],
  });
}
function sparkchart3() {
  function rgbToHex(r, g, b) {
    return "#" + ((1 << 24) | (r << 16) | (g << 8) | b).toString(16).slice(1);
  }
  spark3.updateOptions({
    colors: [
      rgbToHex(
        myVarVal.split(",")[0],
        myVarVal.split(",")[1],
        myVarVal.split(",")[2]
      ),
      "rgb(203,213,225)",
    ],
  });
}
function sparkchart4() {
  function rgbToHex(r, g, b) {
    return "#" + ((1 << 24) | (r << 16) | (g << 8) | b).toString(16).slice(1);
  }
  spark4.updateOptions({
    colors: [
      rgbToHex(
        myVarVal.split(",")[0],
        myVarVal.split(",")[1],
        myVarVal.split(",")[2]
      ),
      "rgb(203,213,225)",
    ],
  });
}
function visitorschart() {
  function rgbToHex(r, g, b) {
    return "#" + ((1 << 24) | (r << 16) | (g << 8) | b).toString(16).slice(1);
  }
  chart1.updateOptions({
    colors: [
      rgbToHex(
        myVarVal.split(",")[0],
        myVarVal.split(",")[1],
        myVarVal.split(",")[2]
      ),
    ],
    fill: {
      type: "gradient",
      gradient: {
        shadeIntensity: 1,
        opacityFrom: 0.7,
        opacityTo: 1,
        colorStops: [
          {
            offset: 0,
            color: "#60a5fa",
            opacity: 1,
          },
          {
            offset: 100,
            color: "rgb(" + myVarVal + ")",
            opacity: 1,
          },
        ],
      },
    },
  });
}
function salesdonut() {
  myChart.data.datasets[0] = {
    data: [50, 30, 30],
    backgroundColor: ["rgb(" + myVarVal + ")", "rgb(96, 165, 250)"],
    borderWidth: 0,
  };
  myChart.update();
}
