<div class="bg-light rounded">
    <div class="card">
        <div class="card-body">
            <div class="container">
                <h5 class="card-title">Dashboard</h5>

                <div class="container mt-4">
                    <div class="row">
                        <div class="col-md-5">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">Jumlah</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Data PEP Found</td>
                                        <td><b>{{number_format($totalFound)}}</b></td>
                                    </tr>  
                                    <tr>
                                        <td>Data PEP NOT Found</td>
                                        <td><b>{{number_format($totalNotFound)}}</b></td>
                                    </tr> 
                                    <tr>
                                        <td>Error / lainnya</td>
                                        <td><b>{{number_format($totalOthers)}}</b></td>
                                    </tr>
                                    <tr>
                                        <td><b>Total Pengecekan Nasabah</b></td>
                                        <td><b>{{number_format($totalCount)}}</b></td>
                                    </tr>                                       
                                </tbody>
                            </table>

                            <div class="mt-3">
                                Tanggal Pengecekan Terakhir: <br/>
                                <b>{{$latestDateUsage}}</b>
                            </div>

                        </div>

                        <div class="col-md-7">
                            <canvas id="myBarChart" width="400" height="280"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('myBarChart').getContext('2d');
    const currentYear = new Date().getFullYear();
    const myBarChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [
                'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun',
                'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
            ],
            datasets: [{
                label: 'Total DATA FOUND in ' + currentYear,
                data: @json($data), // Mengirim data dari controller ke JavaScript
                backgroundColor: [
                    'rgba(0, 0, 255, 0.2)',
                ],
                borderColor: [
                    'rgb(0, 0, 255)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>