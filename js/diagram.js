$(document).ready(function(){
    $.ajax({
      url: "http://cs304/classes/DoctorStats.php",
      method: "GET",
      success: function(data) {
        console.log(data);
        var disease = [];
        var total = [];
  
        for(var i in data) {
          disease.push(data[i].previous_diseases);
          total.push(data[i].total);
        }
  
        var chartdata = {
          labels: disease,
          datasets : [
            {
              label: 'Disease Total',
              backgroundColor: [
                'rgba(255,166,166)',
                'rgba(255,236,201)',
                'rgba(255,244,201)',
                'rgba(208,243,238)',
                'rgba(216,233,243)'
            ],
              borderColor: 'rgba(200, 200, 200, 0.75)',
              hoverBackgroundColor: 'rgba(200, 200, 200, 1)',
              hoverBorderColor: 'rgba(200, 200, 200, 1)',
              data: total
            }
          ]
        };
  
        var ctx = $("#mycanvas");
  
        var barGraph = new Chart(ctx, {
          type: 'doughnut',
          data: chartdata
        });
      },
      error: function(data) {
        console.log(data);
      }
    });
  });