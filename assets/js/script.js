$(document).ready(function() {
    // Fetch passengers and render chart on page load
    fetchPassengers();
    renderChart();

    // Function to fetch passengers
    function fetchPassengers() {
        $.ajax({
            url: 'server/read.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                let passengerData = '';
                if (response.records) {
                    response.records.forEach(function(passenger) {
                        passengerData += `
                            <tr>
                                <td>${passenger.id}</td>
                                <td>${passenger.first_name}</td>
                                <td>${passenger.last_name}</td>
                                <td>${passenger.email}</td>
                                <td>${passenger.phone}</td>
                                <td>${passenger.address}</td>
                                <td>
                                    <button class="btn btn-info btn-sm view-btn" data-id="${passenger.id}">View</button>
                                    <button class="btn btn-warning btn-sm edit-btn" data-id="${passenger.id}">Edit</button>
                                    <button class="btn btn-danger btn-sm delete-btn" data-id="${passenger.id}">Delete</button>
                                </td>
                            </tr>
                        `;
                    });
                }
                $('#passengerData').html(passengerData);
            },
            error: function(xhr, status, error) {
                console.error("Error fetching passengers: " + error);
            }
        });
    }

    // Add Passenger
    $('#addPassengerBtn').on('click', function() {
        let formData = {
            first_name: $('#first_name').val(),
            last_name: $('#last_name').val(),
            email: $('#email').val(),
            phone: $('#phone').val(),
            address: $('#address').val()
        };

        $.ajax({
            url: 'server/create.php',
            type: 'POST',
            data: JSON.stringify(formData),
            contentType: 'application/json',
            success: function(response) {
                $('#addPassengerModal').modal('hide');
                $('#addPassengerForm')[0].reset();
                fetchPassengers();
                renderChart();
                toastManager.showToast('Passenger added successfully!');
            },
            error: function(xhr, status, error) {
                console.error("Error adding passenger: " + error);
                toastManager.showToast('Error adding passenger.', 'error');
            }
        });
    });

    // More JS code for Edit, Delete, View will be added here

    // Edit Passenger - Step 1: Fetch and show data in modal
    $('#passengerData').on('click', '.edit-btn', function() {
        let id = $(this).data('id');
        $.ajax({
            url: 'server/read_one.php?id=' + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#edit_id').val(response.id);
                $('#edit_first_name').val(response.first_name);
                $('#edit_last_name').val(response.last_name);
                $('#edit_email').val(response.email);
                $('#edit_phone').val(response.phone);
                $('#edit_address').val(response.address);
                $('#editPassengerModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error("Error fetching passenger data: " + error);
                toastManager.showToast('Error fetching passenger data.', 'error');
            }
        });
    });

    // Edit Passenger - Step 2: Update database
    $('#updatePassengerBtn').on('click', function() {
        let formData = {
            id: $('#edit_id').val(),
            first_name: $('#edit_first_name').val(),
            last_name: $('#edit_last_name').val(),
            email: $('#edit_email').val(),
            phone: $('#edit_phone').val(),
            address: $('#edit_address').val()
        };

        $.ajax({
            url: 'server/update.php',
            type: 'POST',
            data: JSON.stringify(formData),
            contentType: 'application/json',
            success: function(response) {
                $('#editPassengerModal').modal('hide');
                fetchPassengers();
                renderChart();
                toastManager.showToast('Passenger updated successfully!');
            },
            error: function(xhr, status, error) {
                console.error("Error updating passenger: " + error);
                toastManager.showToast('Error updating passenger.', 'error');
            }
        });
    });

    // Delete Passenger
    $('#passengerData').on('click', '.delete-btn', function() {
        let id = $(this).data('id');
        if (confirm('Are you sure you want to delete this passenger?')) {
            $.ajax({
                url: 'server/delete.php',
                type: 'POST',
                data: JSON.stringify({ id: id }),
                contentType: 'application/json',
                success: function(response) {
                    fetchPassengers();
                    renderChart();
                    toastManager.showToast('Passenger deleted successfully!');
                },
                error: function(xhr, status, error) {
                    console.error("Error deleting passenger: " + error);
                    toastManager.showToast('Error deleting passenger.', 'error');
                }
            });
        }
    });

    // View Passenger
    $('#passengerData').on('click', '.view-btn', function() {
        let id = $(this).data('id');
        $.ajax({
            url: 'server/read_one.php?id=' + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                $('#view_id').text(response.id);
                $('#view_first_name').text(response.first_name);
                $('#view_last_name').text(response.last_name);
                $('#view_email').text(response.email);
                $('#view_phone').text(response.phone);
                $('#view_address').text(response.address);
                $('#viewPassengerModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error("Error fetching passenger data: " + error);
                toastManager.showToast('Error fetching passenger data.', 'error');
            }
        });
    });

    // Toggle between active and archived view
    let archivedView = false;
    $('#viewArchivedBtn').on('click', function() {
        archivedView = !archivedView;
        if (archivedView) {
            fetchArchivedPassengers();
            $(this).text('View Active').removeClass('btn-info').addClass('btn-success');
        } else {
            fetchPassengers();
            $(this).text('View Archived').removeClass('btn-success').addClass('btn-info');
        }
    });

    // Function to fetch archived passengers
    function fetchArchivedPassengers() {
        $.ajax({
            url: 'server/read_archived.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                let passengerData = '';
                if (response.records) {
                    response.records.forEach(function(passenger) {
                        passengerData += `
                            <tr>
                                <td>${passenger.id}</td>
                                <td>${passenger.first_name}</td>
                                <td>${passenger.last_name}</td>
                                <td>${passenger.email}</td>
                                <td>${passenger.phone}</td>
                                <td>${passenger.address}</td>
                                <td>
                                    <button class="btn btn-success btn-sm restore-btn" data-id="${passenger.id}">Restore</button>
                                </td>
                            </tr>
                        `;
                    });
                }
                $('#passengerData').html(passengerData);
            },
            error: function(xhr, status, error) {
                console.error("Error fetching archived passengers: " + error);
                $('#passengerData').html('<tr><td colspan="7">No archived passengers found.</td></tr>');
            }
        });
    }

    // Restore Passenger
    $('#passengerData').on('click', '.restore-btn', function() {
        let id = $(this).data('id');
        if (confirm('Are you sure you want to restore this passenger?')) {
            $.ajax({
                url: 'server/restore.php',
                type: 'POST',
                data: JSON.stringify({ id: id }),
                contentType: 'application/json',
                success: function(response) {
                    fetchArchivedPassengers();
                    renderChart();
                    toastManager.showToast('Passenger restored successfully!');
                },
                error: function(xhr, status, error) {
                    console.error("Error restoring passenger: " + error);
                    toastManager.showToast('Error restoring passenger.', 'error');
                }
            });
        }
    });

    // Function to render chart
    function renderChart() {
        $.ajax({
            url: 'server/read_chart_data.php',
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                var ctx = document.getElementById('passengerChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Active Passengers', 'Archived Passengers'],
                        datasets: [{
                            label: '# of Passengers',
                            data: [response.active_passengers, response.deleted_passengers],
                            backgroundColor: [
                                'rgba(54, 162, 235, 0.2)',
                                'rgba(255, 99, 132, 0.2)'
                            ],
                            borderColor: [
                                'rgba(54, 162, 235, 1)',
                                'rgba(255, 99, 132, 1)'
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
            },
            error: function(xhr, status, error) {
                console.error("Error fetching chart data: " + error);
            }
        });
    }
});
