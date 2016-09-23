function drawChartWithData(builtData, title) {
  google.charts.load("current", {packages:['corechart']});
  google.charts.setOnLoadCallback(drawChart);
  function drawChart() {
    var data = google.visualization.arrayToDataTable(builtData);

    var view = new google.visualization.DataView(data);
    view.setColumns([0, 1,
                     { calc: "stringify",
  //                          sourceColumn: 1,
                       type: "string",
                       role: "annotation" },
                     2]);

    var options = {
      title: title,
      titleTextStyle: { color: '#fff' },

  //         legendTextStyle: { color: '#fff' },

  //         width: 600,
  //         height: 400,
      bar: {groupWidth: "90%"},
      legend: { position: "none" },
      backgroundColor: '#494f57',
      color: '#ffffff',

      vAxis: {
  //           textStyle: { color: '#fff' },

        textStyle: {
          color: '#fff'
        },

        baselineColor: '#fff',
        gridlines: {
          color: '#fff',
        }
      },

      hAxis: {
        textStyle: {
          color: '#fff'
        }
      },

      chartArea: {
  //           left: 50
      }

    };
    var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
    chart.draw(view, options);
  }
}

function buildAvgConcurrentViewersData() {
  // var test = [
  //     ['Stream ID', 'Average Concurrent Viewers', 'Followers Gained', 'Views Gained'],
  //     ['23029099008', 9, 4, 35],
  //     ['2005',  1170,      460],
  //     ['2006',  660,       1120],
  //     ['2007',  1030,      540]
  //   ];
  
  var title = "Average Concurrent Viewers";

  var avgConcurrentViewersData = [];
  var labels = ['Streamer ID', 'Average Concurrent Viewers', { role: 'style'} ];
  var key = 'avg_concurrent_viewers';
  var row = [];

  avgConcurrentViewersData.push(labels);

  for (var streamerId in calculationsToRender) {

    row = [streamerId];

    row.push( calculationsToRender[streamerId][key]);

  //   for (var key in calculationsToRender[streamerId]) {
  //     row.push( calculationsToRender[streamerId][key] );
  //   }

    row.push( '#ffffff' );

    avgConcurrentViewersData.push(row);
    row = [];

  }

  console.log(avgConcurrentViewersData);
 
  drawChartWithData(avgConcurrentViewersData, title);
  
  document.getElementById("avg-concurrent-viewers").className = "selected";
  document.getElementById("followers-gained").className = "";
  document.getElementById("views-gained").className = "";
}

function buildFollowersGainedData() {
  var title = "Followers Gained";
  
  var followersGainedData = [];
  var labels = ['Streamer ID', 'Followers Gained', { role: 'style'} ];
  var key = 'followers_gained';
  var row = [];

  followersGainedData.push(labels);

  for (var streamerId in calculationsToRender) {

    row = [streamerId];

    row.push( calculationsToRender[streamerId][key]);

  //   for (var key in calculationsToRender[streamerId]) {
  //     row.push( calculationsToRender[streamerId][key] );
  //   }

    row.push( '#ffffff' );

    followersGainedData.push(row);
    row = [];

  }

  console.log(followersGainedData);
  
  drawChartWithData(followersGainedData, title);  
  
  document.getElementById("avg-concurrent-viewers").className = "";
  document.getElementById("followers-gained").className = "selected";
  document.getElementById("views-gained").className = "";
}

function buildviewsGainedData() {
  var title = "Views Gained";
  
  var viewsGainedData = [];
  var labels = ['Streamer ID', 'Views Gained', { role: 'style'} ];
  var key = 'views_gained';
  var row = [];

  viewsGainedData.push(labels);

  for (var streamerId in calculationsToRender) {

    row = [streamerId];

    row.push( calculationsToRender[streamerId][key]);

  //   for (var key in calculationsToRender[streamerId]) {
  //     row.push( calculationsToRender[streamerId][key] );
  //   }

    row.push( '#ffffff' );

    viewsGainedData.push(row);
    row = [];

  }

  console.log(viewsGainedData);
  
  drawChartWithData(viewsGainedData, title);    
  
  document.getElementById("avg-concurrent-viewers").className = "";
  document.getElementById("followers-gained").className = "";
  document.getElementById("views-gained").className = "selected";
}


console.log(calculationsToRender);
buildAvgConcurrentViewersData();
document.getElementById("avg-concurrent-viewers").addEventListener("click", buildAvgConcurrentViewersData);
document.getElementById("followers-gained").addEventListener("click", buildFollowersGainedData);
document.getElementById("views-gained").addEventListener("click", buildviewsGainedData);



















