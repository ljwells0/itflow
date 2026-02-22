<?php

require_once '../validate_api_key.php';

require_once '../require_post_method.php';


// Parse ID
$location_id = intval($_POST['location_id']);

// Default
$delete_count = false;

if (!empty($location_id)) {
    $row = mysqli_fetch_assoc(mysqli_query($mysqli, "SELECT location_name FROM locations WHERE location_id = $location_id AND location_client_id = $client_id LIMIT 1"));
    $location_name = isset($row['location_name']) ? $row['location_name'] : '';

    // Remove location_tag links (junction table) so the location can be deleted
    mysqli_query($mysqli, "DELETE FROM location_tags WHERE location_id = $location_id");

    $delete_sql = mysqli_query($mysqli, "DELETE FROM locations WHERE location_id = $location_id AND location_client_id = $client_id LIMIT 1");

    // Check delete & get affected rows
    if ($delete_sql && !empty($location_name)) {
        $delete_count = mysqli_affected_rows($mysqli);

        // Logging
        logAction("Location", "Delete", "$location_name via API ($api_key_name)", $client_id, $location_id);
    }
}

// Output
require_once '../delete_output.php';
