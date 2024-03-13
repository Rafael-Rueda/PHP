<?php
/*
Plugin Name: CSV Reader
Plugin URI: https://github.com/Rafael-Rueda/PHP/tree/main/Plugins-Wordpress/csv-reader
Description: Reads CSV files and displays them on the frontend.
Version: 1.0
Author: Rafael Rueda
Author URI: https://github.com/Rafael-Rueda
*/

// Function to read a single CSV file and return its data
function read_csv_and_return_data($file_path)
{
    $csv_data = array();
    $file_name = basename($file_path, ".csv");

    if (($handle = fopen($file_path, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $csv_data[] = $data;
        }
        fclose($handle);
    }
    return array($file_name => $csv_data);
}

// Function to read data from multiple CSV files
function read_multiple_csv_and_return_data($file_paths)
{
    $all_csv_data = array();
    foreach ($file_paths as $file_path) {
        // $all_csv_data[] = read_csv_and_return_data($file_path);
        $all_csv_data = array_merge($all_csv_data, read_csv_and_return_data($file_path));
    }
    return $all_csv_data;
}

// Shortcode to display CSV data
function display_csv_data_shortcode()
{
    $directory_path = plugin_dir_path(__FILE__) . 'csv_files/';

    $file_paths = glob($directory_path . '*.csv');
    $all_csv_data = read_multiple_csv_and_return_data($file_paths);

    $output = '';
    // return '<pre>' . esc_html(print_r($all_csv_data, true)) . '</pre>'; // For debug proposes

    foreach ($all_csv_data as $file_name => $csv_data) {
        // Start building the output
        $output .= "<h3 class='csv-reader-heading'>" . $file_name . "</h3><table style='width:100%;border-collapse:collapse;' border='1' class='csv-reader-table'>";
        $is_header = true;
        
        foreach ($csv_data as $row_index => $row) {
            if (!$is_header && $row_index === 1) {
                $output .= "<tbody>";
            }
            if ($is_header) {
                $output .= "<thead><tr>";
                foreach ($row as $cell) {
                    $output .= "<th>" . esc_html($cell) . "</th>";
                }
                $output .= "</tr></thead>";
                $is_header = false;
            } else {
                $output .= "<tr>";
                foreach ($row as $cell) {
                    $output .= "<td>" . esc_html($cell) . "</td>";
                }
                $output .= "</tr>";
            }
            if (!$is_header && $row_index === count($csv_data, $mode = COUNT_NORMAL) - 1) {
                $output .= "</tbody>";
            }
        }

        $output .= "</table>";
    }

    return $output;
}
add_shortcode('display_csv_data', 'display_csv_data_shortcode');

// Styles

function csv_reader_enqueue_scripts()
{
    wp_enqueue_style('my-csv-reader-styles', plugin_dir_url(__FILE__) . 'csv-reader-styles.css');
}
add_action('wp_enqueue_scripts', 'csv_reader_enqueue_scripts');