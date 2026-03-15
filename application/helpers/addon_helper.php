<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
if (!function_exists('logMessage')) {

    function logMessage($message, $logFile = FCPATH . '/addons/error_log.txt')
    {
        $formattedMessage = date('Y-m-d H:i:s') . " - " . $message . PHP_EOL;
        file_put_contents($logFile, $formattedMessage, FILE_APPEND);
    }
}



function read_json($file_path)
{

    // Read the file contents
    $jsonData = file_get_contents($file_path);

    // Decode the JSON data to a PHP array
    $data = json_decode($jsonData, true); // Set 'true' to convert to an associative array


    // Check if the decoding was successful
    if ($data === null) {
        logMessage("Failed to read or decode JSON." . $file_path);
        return;
    } else {
        // Access the data
        logMessage("successfully decoded JSON file " . $file_path);
        return $data;
    }
}

function execute_sql($file_path)
{
    $return_array = ['status' => true, 'msg' => 'All queries executed successfully'];
    $CI = &get_instance();

    $sql = file_get_contents($file_path);

    if ($sql === false) {
        logMessage("Error: Unable to read SQL file: $file_path");
        return ['status' => false, 'msg' => 'Error reading the SQL file.'];
    }

    logMessage("Loaded SQL file: $file_path");
    logMessage("Starting multi-query execution...");

    $CI->db->db_debug = false;

    if ($CI->db->conn_id->multi_query($sql)) {

        do {


            if ($result = $CI->db->conn_id->store_result()) {
                $result->free();
            }

            if ($error = $CI->db->conn_id->error) {
                logMessage("SQL ERROR: " . $error);
                $return_array = ['status' => false, 'msg' => "SQL Error: $error"];
                break;
            } else {
                logMessage("Query chunk executed successfully.");
            }
        } while ($CI->db->conn_id->more_results() && $CI->db->conn_id->next_result());
    } else {
        $error = $CI->db->conn_id->error;
        logMessage("SQL ERROR (init): " . $error);
        $return_array = ['status' => false, 'msg' => "SQL Error: $error"];
    }

    logMessage("SQL Execution finished with status: " . ($return_array['status'] ? 'SUCCESS' : 'FAILED'));

    $CI->db->db_debug = true;

    return $return_array;
}






function rmTempDir($dirname) //remove temp directory addon folder if exists
{
    if (is_dir($dirname)) {
        deleteDirectory($dirname);
    }
}

function add_quotes($str)
{
    return sprintf("'%s'", $str);
}

function listInnerFolders($directory)
{
    // Check if the directory exists
    if (!is_dir($directory)) {
        echo "The specified path is not a directory.";
        return;
    }

    // Scan the directory and get all items
    $items = scandir($directory);
    $folders = [];

    // Filter to get only folders
    foreach ($items as $item) {
        if ($item === '.' || $item === '..') {
            continue; // Skip current and parent directory
        }

        $itemPath = "$directory/$item";
        if (is_dir($itemPath)) {
            $folders[] = $item; // Add to the folders array
        }
    }

    return $folders;
}

function copyDirectory($source, $destination)
{
    // Check if the source directory exists
    if (!is_dir($source)) {
        logMessage("Source directory does not exist.");
        return false;
    }

    // Create the destination directory if it doesn't exist
    if (!is_dir($destination)) {
        mkdir($destination, 0755, true);
    }

    // Open the source directory
    $dir = opendir($source);
    if (!$dir) {
        logMessage("Could not open source directory.");
        return false;
    }

    // Loop through the files in the source directory
    while (($file = readdir($dir)) !== false) {
        // Skip the current and parent directory pointers
        if ($file === '.' || $file === '..') {
            continue;
        }

        // Define the source and destination paths
        $sourcePath = $source . DIRECTORY_SEPARATOR . $file;
        $destinationPath = $destination . DIRECTORY_SEPARATOR . $file;

        // Recursively copy if it's a directory
        if (is_dir($sourcePath)) {
            copyDirectory($sourcePath, $destinationPath);
        } else {
            // Copy the file
            logMessage("File was Copied successfully in location -" . $sourcePath);
            copy($sourcePath, $destinationPath);
        }
    }

    // Close the source directory
    closedir($dir);

    return true;
}





function getDirectoryStructure($dir, $backup_json_path) // for creating json backup files
{
    // Ensure the directory exists
    if (!is_dir($dir)) {
        die("Directory does not exist: $dir");
    }

    // Initialize an array to hold the directory paths
    $paths = [];
    $split_path = $dir;
    // Recursive function to scan the directory


    // Start scanning from the root directory
    scanDirectory($dir, $split_path, $paths);


    $jsonData = json_encode($paths,  JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    $check_file_created = createFileIfNotExists($backup_json_path);

    if ($check_file_created) {
        if (file_put_contents($backup_json_path, $jsonData) !== false) {

            logMessage("Data successfully written to " . $backup_json_path);
            return true;
        } else {
            logMessage("Failed to write data to " . $backup_json_path);
            return false;
        }
    } else {
        return false;
    }



    // return $paths;
}



function scanDirectory($dir, $split_path, &$paths)
{
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        $path = $dir . DIRECTORY_SEPARATOR . $item;

        if (is_file($path)) {
            $paths[] =  str_replace($split_path, '', $path); // Add the full path to the array
        }
        if (is_dir($path)) {
            // If item is a directory, recurse into it
            scanDirectory($path, $split_path, $paths);
        }
    }
}


function createFileIfNotExists($filePath)
{
    // Get the directory part of the file path
    $directoryPath = dirname($filePath);

    // Check if the directory exists
    if (!is_dir($directoryPath)) {
        // Attempt to create the directory and its parent directories
        if (mkdir($directoryPath, 0755, true)) {
            logMessage("Directory created successfully.:" . $filePath);
            return true;
        } else {
            logMessage("Failed to create the directory.:" . $filePath);

            return false;
        }
    }

    // Check if the file exists
    if (!file_exists($filePath)) {
        // Attempt to create the file
        $handle = fopen($filePath, 'w');
        if ($handle) {
            fclose($handle);
            logMessage("File created successfully.:" . $filePath);
            return true;
        } else {
            logMessage("Failed to create the file.:" . $filePath);
            return false;
        }
    } else {
        logMessage("File already exists.:" . $filePath);
        return true;
    }
}


function createBackUpFiles($pathsToCopy, $destinationBase) // it will store backup files in specified location
{

    if (is_dir($destinationBase)) {
        deleteDirectory($destinationBase);
    }

    if (!is_dir($destinationBase)) {
        if (!mkdir($destinationBase, 0755, true)) {
            logMessage("Failed to create the directory.:" . $destinationBase);
            return false;
        }
    }



    $jsonData = file_get_contents($pathsToCopy);

    // Decode the JSON data to a PHP array
    $json_Array = json_decode($jsonData, true); // Set 'true' to convert to an associative array



    // Loop through the array and copy each item
    foreach ($json_Array as $path) {
        $sourceFile =  FCPATH . $path;
        $sourceFilePath =  $path;

        if (!file_exists($sourceFile)) {
            continue;
            // return;
        }

        // Get the directory and filename of the source file
        $sourceDir = dirname($sourceFilePath);
        $fileName = basename($sourceFilePath);

        // Construct the destination path
        // Get the relative path from the base directory
        $relativePath = $sourceDir;
        $destinationDir = rtrim($destinationBase, '/') . '/' . ltrim($relativePath, '/') . '/';

        // Create the destination directory if it doesn't exist
        if (!is_dir($destinationDir)) {

            if (!mkdir($destinationDir, 0755, true)) {
                //   echo "Failed to create directory: $destinationDir";
                logMessage("Failed to create the directory.:" . $destinationDir);
                return false;
            }
        }

        // Copy the file to the new destination
        $destinationFile = $destinationDir . $fileName;

        if (copy($sourceFile, $destinationFile)) {
            // echo "File copied successfully to $destinationFile";
            logMessage("File copied successfully.:  " . $sourceFile);
        } else {
            logMessage("Failed to copy file.:" . $sourceFile);
            return false;
        }
    }

    return true;
}



function deleteCopiedFiles($path)
{  //it will delete folder and file of given array

    $files_array = read_json($path);

    if (!empty($files_array['files'])) {
        deleteFilesAndDirectories($files_array['files']);
    }
}

function deleteFilesAndDirectories($array = [])
{  //it will delete folder and file of given array
    foreach ($array as $path) {
        // Check if the path exists
        if (file_exists($path)) {
            if (is_dir($path)) {
                // If it's a directory, delete it recursively
                deleteDirectory($path);
            } else {
                // If it's a file, delete it
                unlink($path);
            }
            // echo "Deleted: $path\n";
        } else {
            // echo "Path does not exist: $path\n";
        }
    }
}

function deleteDirectory($dir)
{ //it will delete folder as well as inner files
    // Scan the directory for files and subdirectories
    $files = array_diff(scandir($dir), ['.', '..']);

    foreach ($files as $file) {
        $filePath = "$dir/$file";
        if (is_dir($filePath)) {
            // Recursively delete subdirectory
            deleteDirectory($filePath);
        } else {
            // Delete file
            unlink($filePath);
        }
    }

    // Remove the now-empty directory
    rmdir($dir);
}
function updateLicense($product_id)
{
    $CI = &get_instance();
    $filename = APPPATH . "config/license.php";

    $searchAddonProd = '$config[\'addon_prod\']';
    $searchAddonVer  = '$config[\'addon_ver\']';

    if (!file_exists($filename)) return;

    $addon_details    = $CI->addons_model->getAddonWithVersion($product_id);
    $addon_prod_array = $CI->config->item('addon_prod');
    $addon_ver_array  = $CI->config->item('addon_ver');

    // Check array existence
    if (!is_array($addon_prod_array) || !is_array($addon_ver_array)) {
        return; // or logMessage + return
    }

    // REMOVE old values if present
    if (($key = array_search($addon_details->addon_prod, $addon_prod_array)) !== false) {
        unset($addon_prod_array[$key]);
    }
    if (($key = array_search($addon_details->addon_ver, $addon_ver_array)) !== false) {
        unset($addon_ver_array[$key]);
    }

    // ADD new values
    $addon_prod_array[] = $addon_details->addon_prod;
    $addon_ver_array[]  = $addon_details->addon_ver;

    // Remove duplicates + reindex
    $addon_prod_array = array_values(array_unique($addon_prod_array));
    $addon_ver_array  = array_values(array_unique($addon_ver_array));

    // Convert arrays into config code
    $addon_prod_array_comma = implode(',', array_map(fn($v) => "'$v'", $addon_prod_array));
    $addon_ver_array_comma  = implode(',', array_map(fn($v) => "'$v'", $addon_ver_array));

    $newAddonProd = "$searchAddonProd = array($addon_prod_array_comma);";
    $newAddonVer  = "$searchAddonVer = array($addon_ver_array_comma);";

    // Replace lines in file
    $lines = file($filename);
    foreach ($lines as &$line) {
        if (strpos($line, $searchAddonProd) !== false) {
            $line = $newAddonProd . PHP_EOL;
        } elseif (strpos($line, $searchAddonVer) !== false) {
            $line = $newAddonVer . PHP_EOL;
        }
    }

    file_put_contents($filename, implode('', $lines));
}
