<?php
// use NameSpace
use backend\models\UserModel;
use backend\models\UserPermissionModel;

// $userList = UserModel::getInstance()->getAllModels();

?>

<!-- Chart JS Lib -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Chart
    var revenueChart = new Chart(document.getElementById('Revenue_Income'), {
        type: 'line',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June'],
            datasets: [{
                label: 'Revenue',
                data: [1000, 2000, 1500, 3000, 2500, 4000],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
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

    // Total Login Chart
    var totalLoginChart = new Chart(document.getElementById('Total_Login'), {
        type: 'bar',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June'],
            datasets: [{
                label: 'Total Login',
                data: [50, 100, 75, 120, 90, 150],
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
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