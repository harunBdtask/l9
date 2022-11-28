<div class="col-xs-12 col-sm-12 col-md-12 col-lg-8 col-lg-offset-2">
    <div class="row-col box  bg">
        <div class="col-sm-8">
            <div class="box-header tile">
                <h3>Order Volume Report</h3>
            </div>
            <div>
                <canvas id="bar-chart-grouped" width="auto" height="auto"></canvas>
            </div>

        </div>

    </div>
</div>

<script>
    $(document).ready(function () {
        let reportData = null;
        let colors = null;
        let buyers = null;
        let fromDate = $("#from_date").val();
        let toDate = $("#to_date").val();
        $.ajax({
            url: `/order-volume-report/get-report-data?from_date=${fromDate}&to_date=${toDate}`,
            type: "get",
            dataType: "json",
            success(response) {
                reportData = response.totalValue;
                buyers = response.buyers;
                colors = response.colors;
            },
            complete() {
                new Chart(document.getElementById("bar-chart-grouped"), {
                    type: 'bar',
                    data: {
                        labels: buyers,
                        datasets: [
                            {
                                backgroundColor: colors,
                                data: reportData,
                                label: "PO Value",
                            }
                        ]
                    },

                    options: {
                        layout: {
                            padding: {
                                left: 20,
                                right: 20
                            }

                        },
                        title: {
                            display: true,
                            text: 'Order Volume Report'
                        },
                        hover: {
                            animationDuration: 0
                        },
                        legend: false,
                        scales: {
                            yAxis: {
                                display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Quantity'
                                }
                            }
                        },
                    },
                    responsive: true,
                    maintainAspectRatio: false

                });
            }
        });
    });
</script>