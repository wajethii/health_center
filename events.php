<?php
session_start();

// Check if the user is not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Redirect to the login page
    exit(); // Stop further execution
}

include "includes/header.php";
?>
<div class="container bg-white mx-auto mt-5 max-w-7xl p-4 shadow-md sm:px-4 lg:px-4">
    <!-- Container for calendar -->
    <div id="calendar" class="mt-5 px-4"></div>
</div>



<script>
   document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        editable: true, // Allow event editing
        selectable: true, // Allow creating new events

        // Fetch events from PHP backend
        events: {
            url: 'event_.php',
            method: 'GET'
        },

        // Handle event creation
        select: function(arg) {
            var title = prompt('Event Title:');
            if (title) {
                var eventData = {
                    title: title,
                    start: arg.startStr,
                    end: arg.endStr,
                    description: '' // Optional
                };

                // Send event data to PHP backend for saving
                saveEvent(eventData);
            }
            calendar.unselect();
        },

        // Handle event deletion
        eventClick: function(info) {
            if (confirm("Are you sure you want to delete this event?")) {
                // Send event ID to PHP backend for deletion
                deleteEvent(info.event.id);
            }
        }
    });

    calendar.render();

    // Function to save event data to PHP backend
    function saveEvent(eventData) {
        fetch('event_.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(eventData),
        })
        .then(response => response.json())
        .then(data => {
            console.log('Success:', data);
            calendar.refetchEvents(); // Refresh calendar events after save
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }

    // Function to delete event data from PHP backend
    function deleteEvent(eventId) {
        fetch('event_.php', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + eventId,
        })
        .then(response => response.json())
        .then(data => {
            console.log('Success:', data);
            calendar.refetchEvents(); // Refresh calendar events after deletion
        })
        .catch((error) => {
            console.error('Error:', error);
        });
    }
});

</script>