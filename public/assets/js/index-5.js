// for NFTs Statistics

var options = {
  series: [
    {
      name: "Sales",
      data: [20, 38, 38, 72, 55, 63, 43, 76, 55, 80, 40, 80],
    },
    {
      name: "Income",
      data: [85, 65, 75, 38, 85, 35, 62, 40, 40, 64, 50, 89],
    },
  ],
  chart: {
    height: 420,
    type: "line",
    toolbar: {
      show: false,
    },
    zoom: {
      enabled: false,
    },
    dropShadow: {
      enabled: true,
      enabledOnSeries: undefined,
      top: 5,
      left: 0,
      blur: 6,
      color: "#000",
      opacity: 0.1,
    },
  },
  dataLabels: {
    enabled: false,
  },
  legend: {
    position: "top",
    horizontalAlign: "center",
    offsetX: -15,
    fontWeight: "bold",
  },
  stroke: {
    curve: "straight",
    width: "3",
    dashArray: [0, 5],
  },
  grid: {
    borderColor: "#f2f6f7",
  },
  colors: ["rgb(90,102,241)", "rgb(203,213,225)"],
  yaxis: {
    labels: {
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
    },
  },
};
document.getElementById("nft-statistics").innerHTML = "";
var chart = new ApexCharts(document.querySelector("#nft-statistics"), options);
chart.render();
function nftStatistics() {
  chart.updateOptions({
    colors: ["rgb(" + myVarVal + ")", "#d1dae5"],
  });
}

// for featured collections
var swiper = new Swiper(".pagination-dynamic", {
  pagination: {
    el: ".swiper-pagination",
    dynamicBullets: true,
    clickable: true,
  },
  loop: true,
  autoplay: {
    delay: 1500,
    disableOnInteraction: false,
  },
});
