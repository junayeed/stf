function showTop10Countries()
{
    $('#top_customers').highcharts
    (
        {
            chart: { type: 'bar'},
            title: { text: 'Top 10 Countries' },
            //subtitle: { text: 'By total purchase' },
            xAxis: { categories: countryArray },
            yAxis: 
            {
                min: 0,
                title: { text: '' }
            },
            tooltip: 
            {
                headerFormat: '<span style="font-size:12px; font-weight: bold">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0; font-size:13px;">Total: </td>' +
                             '<td style="padding:0">{point.y:.2f}</td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: 
            {
                column: 
                {
                    pointPadding: 0.0,
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
                    data: countryTotalArray
                }
            ]
        }
    );   
}

function showPieCharts(male, female) 
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
                text: 'Male Vs Female'
            },
            tooltip: 
            {
        	    //pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
        	    pointFormat: '<b>{point.percentage:.1f}%</b>'
            },
            plotOptions: 
            {
                pie: 
                {
                    size:'80%',
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: 
                    {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        distance: 5,
                        format: '<b>{point.name}</b><br>{point.percentage:.1f} %'
                        
                    }
                }
            },
            credits: {
                enabled: false
            },
            series: 
            [
                {
                    type: 'pie',
                    //name: 'Browser share',
                    data: [ ['Male', male], ['Female', female] ]
            }]
        });
}


function loadMap()
{
    $('#world-map').vectorMap
    (
        {
            map: 'world_mill_en',
            series: 
            {
                regions: 
                [
                    {
                        values: countryData,
                        scale: ['#C8EEFF', '#2A93FC'],
                        normalizeFunction: 'polynomial'
                    }
                ]
            },
            onRegionLabelShow: function(e, el, code)
            {
                if (countryData[code])
                {
                    el.html(el.html()+' (Applicants:'+countryData[code]+')');
                }
                else
                {
                    el.html(el.html()+' (Applicants: 0)');
                }
            } 
        }
    );
}