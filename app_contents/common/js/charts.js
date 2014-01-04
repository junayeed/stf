function showTop10customers()
{
    $('#top_customers').highcharts
    (
        {
            chart: { type: 'bar'},
            title: { text: 'Top 10 Customers' },
            subtitle: { text: 'By total purchase' },
            xAxis: { categories: companyArray },
            yAxis: 
            {
                min: 0,
                title: { text: '' }
            },
            tooltip: 
            {
                headerFormat: '<span style="font-size:11px; font-weight: bold">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0; font-size:11px;">Amount: </td>' +
                             '<td style="padding:0">{point.y:.2f}</td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: 
            {
                column: 
                {
                    pointPadding: 0.1,
                    borderWidth: 0
                }
            },
            credits: 
            {
                enabled: false
            },
            series: 
            [
                {
                    showInLegend: false,  
                    name: 'Total Amount',
                    //data: [71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5]
                    data: companyTotalArray
                }
            ]
        }
    );   
}

function showYearWiseCharts()
{
    $('#container').highcharts
    (
        {
            chart: { type: 'column'},
            title: { text: 'Yearly Sale' },
            //subtitle: { text: 'By total purchase' },
            xAxis: { categories: monthArray },
            yAxis: 
            {
                min: 0,
                title: { text: '' }
            },
            tooltip: 
            {
                headerFormat: '<span style="font-size:11px; font-weight: bold">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0; font-size:11px;">Amount: </td>' +
                             '<td style="padding:0; font-size:11px;">{point.y:.2f}</td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: 
            {
                column: 
                {
                    pointPadding: 0, // previous value was 0.2
                    borderWidth: 0
                }
            },
            credits: 
            {
                enabled: false
            },
            series: 
            [
                {
                    showInLegend: false,  
                    name: 'Total Amount',
                    //data: [71.5, 106.4, 129.2, 144.0, 176.0, 135.6, 148.5]
                    data: monthlyTotalArray
                }
            ]
        }
    );   
}


function showPieCharts() 
{
    	
    // Radialize the colors
    Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function(color) {
        return {
            radialGradient: { cx: 0.5, cy: 0.3, r: 0.7 },
            stops: [
                [0, color],
                [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
            ]
        };
    });
		
    // Build the chart
    $('#container').highcharts
    (
        {
            chart: 
            {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: 
            {
                text: 'Browser market shares at a specific website, 2010'
            },
            tooltip: 
            {
        	    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: 
            {
                pie: 
                {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: 
                    {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        formatter: function() 
                        {
                            return '<b>'+ this.point.name +'</b>: '+ this.percentage +' %';
                        }
                    }
                }
            },
            series: 
            [
                {
                    type: 'pie',
                    name: 'Browser share',
                    data: [ 45, 26.8, 8.5, 6.2, 0.7 ]
            }]
        });
}