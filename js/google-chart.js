console.log(calculationsToRender);


var data2 = [
    ["Streamer ID", "Average Concurrent Viewers", { role: "style" } ],
    ["Copper", 8.94, "#b87333"],
    ["Silver", 10.49, "silver"],
    ["Gold", 19.30, "gold"],
    ["Platinum", 21.45, "color: #e5e4e2"]
  ]

var data2 = [];
var labels = ['Streamer ID', 'Average Concurrent Viewers', { role: 'style' }];
var key = 'avg_concurrent_viewers';
var row = [];

data2.push(labels);

for (var streamerId in calculationsToRender) {
  
  row = [streamerId];
  
  row.push( calculationsToRender[streamerId][key] );
  
  row.push( '#fff' );
  
  data2.push(row);
  row = [];
  
}

console.log(data2);


google.charts.load("current", {packages:['corechart']});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
  var data = google.visualization.arrayToDataTable(data2);

  var view = new google.visualization.DataView(data);
  view.setColumns([0, 1,
                   { calc: "stringify",
//                      sourceColumn: 1,
                     type: "string",
                     role: "annotation" },
                   2]);

  var options = {
    title: "Average Concurrent Viewers",
    titleTextStyle: { color: '#fff' },
    
    width: 600,
    height: 400,
    bar: {groupWidth: "85%"},
    legend: { position: "none" },
    
    backgroundColor: '#494f57',
    
    vAxis: {
      textStyle: {
        color: '#fff'
      },
      
      baselineColor: '#fff',
      gridlines: {
        color: '#fff'
      }
      
      
    },
    
    hAxis: {
      textStyle: {
        color: '#fff'
      }
  }
  };
  var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
  chart.draw(view, options);
}




// var test = [];
// var labels = ['Stream ID', 'Average Concurrent Viewers', 'Followers Gained', 'Views Gained'];
// var row = [];

// test.push(labels);


// for (var streamerId in calculationsToRender) {
  
//   row = [streamerId];
  
//   for (var key in calculationsToRender[streamerId]) {
//     row.push( calculationsToRender[streamerId][key] );
//   }
  
//   test.push(row);
//   row = [];
  
// }

// console.log(test);

// google.charts.load('current', {'packages':['corechart']});
// google.charts.setOnLoadCallback(drawChart);

// function drawChart() {
//   var data = google.visualization.arrayToDataTable(test);

//   var options = {
// //     title: 'Company Performance',
// //     curveType: 'function',
//     legend: { position: 'bottom' },
    
// //     backgroundColor: '#494f57'
//   };

//   var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

//   chart.draw(data, options);
// }















