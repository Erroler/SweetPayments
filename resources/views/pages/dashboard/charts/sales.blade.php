<script>
    (async () => {
        const canvas = document.getElementById('sales_chart')
        const context = canvas.getContext('2d');
        canvas.height = 290;


        const getGraphdata = async (timeframe = 30, show_type = 'revenue', subscription = 'all', subscription_name = '') => {
             // AJAX REQUEST!
            const api_endpoint = new URL('{{ (config('app.env') === 'local') ? 'http:' : '' }}{{ route('panel.dashboard.sales') }}');
            api_endpoint.searchParams.append('timeframe', timeframe);
            api_endpoint.searchParams.append('show_type', show_type);
            api_endpoint.searchParams.append('subscription', subscription);

            const graph_data = await fetch(api_endpoint).then(response => response.json());

            let title = 'Displaying ';

            if(show_type === 'revenue')
                title += 'revenue for the last ';
            else 
                title += 'number of sales for the last ';

            if(timeframe <= 90)
                title += timeframe + ' days';
            else
                title += Number(timeframe/30).toFixed(0) + ' months';
                
            if(subscription === 'all')
                title += '.';
            else
                title += ' for subscription ' + subscription_name + '.';
            

            graph_data.title = title;
            return graph_data;
        };

        const initial_graph_data = await getGraphdata();
        let salesChart = new Chart(context, {
            type: 'line',
            data: initial_graph_data,
            options: {
                title: {
                    display: true,
                    text: initial_graph_data.title
                },
                scales: {
                    xAxes: [{
                        type: 'time',
                        ticks: {
                            autoSkip: true,
                            maxTicksLimit: 12
                        },
                        labelFormatter: function (e) {
                            return CanvasJS.formatDate(e.value, "DD MMM");
                        },
                        time: {
                            displayFormats: {
                                'day': 'DD MMM'
                            },
                            tooltipFormat: 'DD/MM/YYYY',
                            unit: 'day',
                        }
                    }],
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            callback: function(value) {
                                return Number(value).toFixed(0) + '€';
                            }
                        },
                    }],
                },
                responsive: true, 
                maintainAspectRatio: false,
                tooltips: {
                    callbacks: {
                        label: function(t, d) {
                            return 'Revenue: ' + Number(t.yLabel).toFixed(2) + '€';
                        },
                        //title: function(t, d) {
                        //    return moment(t[0].xLabel).format('DD/MM/YYYY');
                        //},
                    },
                    intersect: false
                }
            },
        });

        Date.locale = {
            en: {
                month_names: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                month_names_short: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            }
        };

        Date.prototype.getMonthNameShort = function(lang) {
            lang = lang && (lang in Date.locale) ? lang : 'en';
            return Date.locale[lang].month_names_short[this.getMonth()];
        };


        document.querySelector('#sales_chart_options').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const display_type = this.elements['show_type'].value;
            const timeframe = this.elements['timeframe'].value;
            salesChart.data = await getGraphdata(timeframe, display_type, this.elements['subscription'].value, this.elements['subscription'].querySelector(':checked').innerText);
            
            if(timeframe > 90) {
                salesChart.options.scales.xAxes[0].time.unit = 'month';
                salesChart.options.scales.xAxes[0].time.tooltipFormat = 'MMMM YYYY';

                // Format dataset to month format.
                /*const labels = salesChart.data.labels;
                const values = salesChart.data.datasets[0].data;
                const months = salesChart.data.labels.map(element =>{
                    let date = (new Date(element));
                    return (new Date(element)).getMonthNameShort();
                });
                console.log(months);
                */
            } else {
                salesChart.options.scales.xAxes[0].time.unit = 'day';
                salesChart.options.scales.xAxes[0].time.tooltipFormat = 'DD/MM/YYYY';
            }

            if(display_type === 'revenue') {
                salesChart.options.scales.yAxes[0].ticks.callback = (value) => {
                    return Number(value).toFixed(0) + '€';
                };
                salesChart.options.tooltips.callbacks.label = (t, d) => {
                    return 'Revenue: ' + t.yLabel.toFixed(2) + '€';
                }
                salesChart.options.title = {
                    display: true,
                    text: salesChart.data.title
                };
            } else {
                salesChart.options.scales.yAxes[0].ticks.callback = (value) => {
                    if (value % 1 === 0) {return value;}
                };
                salesChart.options.tooltips.callbacks.label = (t, d) => {
                    return 'Number of sales: ' + t.yLabel;
                }
                salesChart.options.title = {
                    display: true,
                    text: salesChart.data.title
                };
            }

            salesChart.update();
        });
    })();
</script>