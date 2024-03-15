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

// Function to read titles (names) of multiple CSV files
function read_multiple_csv_and_return_title_of_files($file_paths)
{
    $titles = array();

    foreach ($file_paths as $file_path) {
        $file_name = basename($file_path, ".csv");
        $titles[] = $file_name;
    }

    return $titles;
}

// Shortcode to display CSV data
function display_csv_data_shortcode() {
    // Pagination setup
    $current_page = isset($_GET['page']) ? intval($_GET['page']) : 1;
    $rows_per_page = 50; // Adjust the number of rows per page as needed

    $directory_path = plugin_dir_path(__FILE__) . 'csv_files/';
    $file_paths = glob($directory_path . '*.csv');
    $all_csv_titles = read_multiple_csv_and_return_title_of_files($file_paths);

    $output = '';

    if(! isset($_GET['data'])){
        foreach ($all_csv_titles as $file_name) {
            $output .= '<h3><a href="?data=' . urlencode($file_name) . '">' . $file_name . '</a></h3>'; // File name as title
        }
    } else {
        // Check if a specific file is requested
        if(isset($_GET['data'])){
            $file_name_requested = $_GET['data'];
            $file_path = plugin_dir_path(__FILE__) . 'csv_files/' . $file_name_requested . '.csv';

            // Check if the file exists before trying to read it
            if(file_exists($file_path)) {
                $csv_data = read_csv_and_return_data($file_path);

                if(!empty($csv_data[$file_name_requested])) {
                    $output .= '<h3>' . $file_name_requested . '</h3>';
                    $output .= '<table class="csv-reader-table">';
                    $output .= '<thead><tr>';
                    // Output table headers (TH)
                    foreach ($csv_data[$file_name_requested][0] as $header) {
                        $output .= '<th>' . $header . '</th>';
                    }
                    $output .= '</tr></thead>';
                    $output .= '<tbody>';
                    // Output table data (TD)
                    for ($i = 1; $i < count($csv_data[$file_name_requested]); $i++) {
                        $output .= '<tr>';
                        foreach ($csv_data[$file_name_requested][$i] as $cell) {
                            $output .= '<td>' . $cell . '</td>';
                        }
                        $output .= '</tr>';
                    }
                    $output .= '</tbody>';
                    $output .= '</table>';
                } else {
                    $output .= '<p>File data could not be loaded or is empty.</p>';
                }
            } else {
                $output .= '<p>Requested file not found!</p>';
            }
        } else {
            $output .= '<p>No file specified.</p>';
        }
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
