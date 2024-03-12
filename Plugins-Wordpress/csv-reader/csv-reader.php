<?php
/*
Plugin Name: CSV Reader
Plugin URI: https://github.com/Rafael-Rueda
Description: Reads CSV files and displays them on the frontend.
Version: 1.0
Author: Rafael Rueda
Author URI: https://github.com/Rafael-Rueda
*/

// Function to read a single CSV file and return its data
function read_csv_and_return_data($file_path) {
    $csv_data = array();
    if (($handle = fopen($file_path, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $csv_data[] = $data;
        }
        fclose($handle);
    }
    return $csv_data;
}

// Function to read data from multiple CSV files
function read_multiple_csv_and_return_data($file_paths) {
    $all_csv_data = array();
    foreach ($file_paths as $file_path) {
        $all_csv_data = array_merge($all_csv_data, read_csv_and_return_data($file_path));
    }
    return $all_csv_data;
}

// Shortcode to display CSV data
function display_csv_data_shortcode() {
    $directory_path = plugin_dir_path(__FILE__) . 'csv_files/';

    $file_paths = glob($directory_path . '*.csv');
    $all_csv_data = read_multiple_csv_and_return_data($file_paths);

    // Start building the output
    $output = "<table style='width:100%;border-collapse:collapse;' border='1'>";
    foreach ($all_csv_data as $row) {
        $output .= "<tr>";
        foreach ($row as $cell) {
            $output .= "<td>" . esc_html($cell) . "</td>";
        }
        $output .= "</tr>";
    }
    $output .= "</table>";

    return $output;
}
add_shortcode('display_csv_data', 'display_csv_data_shortcode');