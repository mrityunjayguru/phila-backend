@extends('layouts.backend.master')

@section('content')
	<style>
		#chartdiv {width: 100%;height: 500px;}
		.dashboard-card {height: auto;}
		#myChart-3 {height: 200px!important; width: 200px!important;}
		.dashboard-stat-card .dash-card-body {padding: 13px;}
	</style>
	<div class="dashboard-box">
		<div class="row">
			<!-- start box 01 -->
			<!--<div class="col-12 col-lg-6 col-xl-3 mb-3">
				<div class="dashboard-card red-card">
					<div class="dash-card-header">
						<h2>Tracking Code Statistics</h2>
						<img src="{{asset('adminAssets/images/tracking-code.svg')}}" alt="" class="img-fluid">
					</div>
					<div class="dash-card-body">
						<div class="dash-card-statis">
							<div class="statis-box">
								<span>This Month</span>
								<h3>492</h3>
							</div>
							<div class="statis-box">
								<span>Overall</span>
								<h3>4,653</h3>
							</div>
						</div>
						<div class="dash-chart-box">
							<span>Last 6 Month Record</span>
							<canvas id="myChart" width="400" height="400"></canvas>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-lg-6 col-xl-3 mb-3">
				<div class="dashboard-card yellow-card">
					<div class="dash-card-header">
						<h2>Buses<br/>List</h2>
						<img src="{{asset('adminAssets/images/buses-list.svg')}}" alt="" class="img-fluid">
					</div>
					<div class="dash-card-body">
						<div class="dash-card-statis">
							<div class="statis-box">
								<span>This Month</span>
								<h3>136</h3>
							</div>
							<div class="statis-box">
								<span>Today</span>
								<h3>12</h3>
							</div>
						</div>
						<div class="dash-chart-box">
							<span>Last 7 Days Record</span>
							<canvas id="myChart-2" width="400" height="400"></canvas>
						</div>
					</div>
				</div>
			</div>-->
			<div class="col-12 col-lg-6 col-xl-3 mb-3">
				<div class="dashboard-card green-card">
					<div class="dash-card-header">
						<h2>Attractions<br/>List</h2>
						<img src="{{asset('adminAssets/images/attraction-list.svg')}}" alt="" class="img-fluid">
					</div>
					<div class="dash-card-body">
						<div class="dash-card-statis">
							<div class="statis-box">
								<span>Things To Do</span>
								<h3 class="text-purple">{{$data->landmark}}</h3>
							</div>
							<div class="statis-box">
								<span>Shopping</span>
								<h3 class="text-yellow">{{$data->shopping}}</h3>
							</div>
							<div class="statis-box">
								<span>Dining</span>
								<h3 class="text-green">{{$data->dining}}</h3>
							</div>
							<div class="statis-box">
								<span>Hotels</span>
								<h3 class="text-red">{{$data->attraction}}</h3>
							</div>
						</div>
						<div class="dash-chart-box">
							<canvas id="myChart-3" width="400" height="400"></canvas>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-lg-6 col-xl-3 mb-3">
				<div class="dashboard-stat-card yell-card">
					<div class="dash-card-header">
						<h2>Stops Statistics</h2>
					</div>
					<div class="dash-card-body">
						<div class="stat-box">
							<div class="stat-imgbox">
								<img src="{{asset('adminAssets/images/stop-statistics.svg')}}" alt="" class="img-fluid">
							</div>
							<div class="stat-detail">
								<span>Total Stops</span>
								<h3 class="text-yellow">{{$data->stops}}</h3>
							</div>
						</div>
					</div>
				</div>
				<div class="dashboard-stat-card purple-card">
					<div class="dash-card-header">
						<h2>Offers Statistics</h2>
					</div>
					<div class="dash-card-body">
						<div class="stat-box">
							<div class="stat-imgbox">
								<img src="{{asset('adminAssets/images/offer-statistic.svg')}}" alt="" class="img-fluid">
							</div>
							<div class="stat-detail">
								<span>Running Offers</span>
								<h3 class="text-purple">{{$data->offers}}</h3>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--<div class="col-12 col-lg-6 col-xl-3 mb-3">
				<div class="dashboard-stat-card red-card">
					<div class="dash-card-header">
						<h2>Users Statistics</h2>
					</div>
					<div class="dash-card-body">
						<div class="stat-box">
							<div class="stat-imgbox">
								<img src="{{asset('adminAssets/images/user-statistic.svg')}}" alt="" class="img-fluid user-stat-icon">
							</div>
							<div class="stat-detail">
								<span>Total Users</span>
								<h3 class="text-red mb-40px">23,456</h3>
								<span>Active Users</span>
								<h3 class="text-red">1,435</h3>
							</div>
						</div>
					</div>
				</div>
			</div>-->
		</div>
	</div>
@endsection

@section('js')
<script>
/*const ctx = document.getElementById('myChart');
const myChart = new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['january', 'Fabruary', 'March','April','May','June'],
        datasets: [{
            label: '# of Votes',
            data: [12, 19, 3, 12, 22, 44],
            backgroundColor: [
                'rgb(255, 99, 132)',
				'rgb(54, 162, 235)',
				'rgb(255, 205, 86)',
				'rgb(177, 127, 38)',
				'rgb(33, 75, 99)'
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

const ctx2 = document.getElementById('myChart-2');
const myChart2 = new Chart(ctx2, {
    type: 'doughnut',
    data: {
        labels: ['13/12/22', '12/2/22', '11/2/22','10/2/22','9/2/22','8/2/22'],
        datasets: [{
            label: '# of Votes',
            data: [12, 19, 3, 22, 5, 15],
            backgroundColor: [
                'rgb(255, 99, 132)',
				'rgb(54, 162, 235)',
				'rgb(255, 205, 86)',
				'rgb(177, 127, 38)',
				'rgb(33, 75, 99)'
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
});*/

const ctx3 = document.getElementById('myChart-3');
const myChart3 = new Chart(ctx3, {
    type: 'doughnut',
    data: {
        labels: ['Things To Do', 'Shopping', 'Dining', 'Hotels'],
        datasets: [{
            label: '# of Votes',
            data: ['{{$data->landmark}}', '{{$data->shopping}}', '{{$data->dining}}', '{{$data->attraction}}'],
            backgroundColor: [
                '#C81C8E',
				'#F49024',
				'#518f30',
				'#CD001C'
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
@endsection