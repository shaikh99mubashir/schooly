<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
class Media_storage
{

    private $_CI;

    public function __construct()
    {
        $this->_CI = &get_instance();
        $this->_CI->load->library('customlib');
    }


    public function fileupload($media_name, $upload_path = "")
    {
        if (!isset($_FILES[$media_name])) {
            return null; // No file uploaded
        }

        $file_error = $_FILES[$media_name]['error'];
        if ($file_error !== UPLOAD_ERR_OK) {
            // Optional: You can handle different error codes here
            return null;
        }

        $tmp_name = $_FILES[$media_name]['tmp_name'];
        $original_name = $_FILES[$media_name]['name'];
        $file_name   = time() . "-" . uniqid(rand()) . "!" . basename($original_name);

        $upload_dir = $this->_CI->customlib->getFolderPath() . $upload_path;

        // Ensure directory exists
        if (!is_dir($upload_dir) && !mkdir($upload_dir, 0755, true)) {
            return null; // Failed to create directory
        }

        // Check if directory is writable
        if (!is_writable($upload_dir)) {
            return null; // Cannot write to directory
        }

        $destination = $upload_dir . $file_name;

        if (is_uploaded_file($tmp_name)) { // Extra check
            if (move_uploaded_file($tmp_name, $destination)) {
                return $file_name;
            }
        }

        return null; // Upload failed
    }


    function getYoutubeThumbnailSize($youtube_url)
    {
        if (empty($youtube_url)) {
            return false;
        }


        $oembed_url = "https://www.youtube.com/oembed?url=" . urlencode($youtube_url) . "&format=json";

        $ch = curl_init($oembed_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpcode !== 200 || !$response) {
            return false;
        }

        $data = json_decode($response, true);

        if (empty($data['thumbnail_url'])) {
            return false;
        }

        $thumbnail_url = $data['thumbnail_url'];

        // 2️⃣ Get image size using HEAD request
        $ch = curl_init($thumbnail_url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_exec($ch);
        $file_size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
        curl_close($ch);



        $size_kb =  self::convertBytesToKB($file_size);

        return [
            'thumbnail_url' => $thumbnail_url,
            'size_kb'       => $size_kb
        ];
    }


    public function fileuploadMultiple($media_name, $upload_path = "")
    {
        if (!isset($_FILES[$media_name])) {
            return [
                "uploaded"            => [],
                "failed"              => [],
                "total_uploaded_size" => 0,
                "total_failed_size"   => 0
            ];
        }

        $uploaded = [];
        $failed   = [];

        $total_uploaded_size = 0;
        $total_failed_size   = 0;

        if (!empty($_FILES[$media_name]['name'][0])) {

            $files = $_FILES[$media_name];

            for ($i = 0; $i < count($files['name']); $i++) {

                $original_name = basename($files['name'][$i]);
                $tmp_name      = $files['tmp_name'][$i];
                $file_size     = $files['size'][$i];
                $file_error    = $files['error'][$i];

                /* FAILED: PHP upload error */
                if ($file_error !== UPLOAD_ERR_OK) {
                    $failed[] = [
                        "name"   => $original_name,
                        "size"   => $file_size,
                        "reason" => $this->uploadErrorMessage($file_error)
                    ];
                    $total_failed_size += $file_size;   // ADD FAILED SIZE
                    continue;
                }

                // Generate saved file name
                $saved_name = time() . "-" . uniqid(rand()) . "!" . $original_name;

                // Directory path
                $upload_dir = $this->_CI->customlib->getFolderPath() . $upload_path;

                if (!is_dir($upload_dir) && !mkdir($upload_dir, 0755, true)) {
                    $failed[] = [
                        "name"   => $original_name,
                        "size"   => $file_size,
                        "reason" => "failed_to_create_directory"
                    ];
                    $total_failed_size += $file_size;
                    continue;
                }

                if (!is_writable($upload_dir)) {
                    $failed[] = [
                        "name"   => $original_name,
                        "size"   => $file_size,
                        "reason" => "directory_not_writable"
                    ];
                    $total_failed_size += $file_size;
                    continue;
                }

                $destination = rtrim($upload_dir, '/') . '/' . $saved_name;

                if (!is_uploaded_file($tmp_name)) {
                    $failed[] = [
                        "name"   => $original_name,
                        "size"   => $file_size,
                        "reason" => "not_an_uploaded_file"
                    ];
                    $total_failed_size += $file_size;
                    continue;
                }

                if (!move_uploaded_file($tmp_name, $destination)) {
                    $failed[] = [
                        "name"   => $original_name,
                        "size"   => $file_size,
                        "reason" => "failed_to_move_file"
                    ];
                    $total_failed_size += $file_size;
                    continue;
                }

                /* SUCCESS */
                $uploaded[] = [
                    "name"       => $original_name,
                    "saved_name" => $saved_name,
                    "size"       => $file_size,
                    "reason"     => null
                ];

                $total_uploaded_size += $file_size;  // ADD UPLOADED SIZE
            }
        }

        return [
            "uploaded"            => $uploaded,
            "failed"              => $failed,
            "total_uploaded_size" => $total_uploaded_size,
            "total_failed_size"   => $total_failed_size
        ];
    }

    public function convertBytesToKB($file_size)
    {
        return round($file_size / 1024); // Rounded to nearest whole number (no decimals)
    }

    public function getTmpFileSize($media_name)
    {
        // Check if file is uploaded and exists in temp folder
        if (
            isset($_FILES[$media_name]) &&
            isset($_FILES[$media_name]['tmp_name']) &&
            $_FILES[$media_name]['error'] !== UPLOAD_ERR_NO_FILE
        ) {
            $file_size = ($_FILES[$media_name]['size']);

            // Ensure size is greater than zero
            if ($file_size > 0) {

                return $this->convertBytesToKB($file_size);
            }
        }

        // Default if no file or zero size
        return 0;
    }

    public function getTmpGoogleDriveFileSize($docs, $token)
    {
        $total_size = 0;


        if (is_string($docs)) {
            $docs = [['id' => $docs]];
        }


        foreach ($docs as $doc) {

            if (!isset($doc['id'])) {
                continue;
            }

            $fileId = $doc['id'];

            $url = "https://www.googleapis.com/drive/v3/files/{$fileId}?fields=size";

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer {$token}"
            ]);

            $res = curl_exec($ch);
            curl_close($ch);

            $json = json_decode($res, true);

            if (isset($json['size'])) {
                $total_size += (int)$json['size'];
            }
        }

        return $this->convertBytesToKB($total_size);
    }



    public function getTmpMultipleFileSize($media_name)
    {
        // Check if files are uploaded
        if (isset($_FILES[$media_name]) && !empty($_FILES[$media_name]['name'][0])) {
            $files = $_FILES[$media_name];
            $total_size = 0;

            for ($i = 0; $i < count($files['name']); $i++) {
                if ($files['error'][$i] === UPLOAD_ERR_OK && $files['size'][$i] > 0) {
                    $total_size += $files['size'][$i];
                }
            }

            return $this->convertBytesToKB($total_size);
        }

        // No files or all zero-size
        return 0;
    }

    public function getUploadedFileSize($file_name, $file_path = "")
    {
        // Get base folder path
        $base_path = $this->_CI->customlib->getFolderPath();

        // Build full file path depending on whether $file_path is empty
        if ($file_path == "") {
            $file_url = $base_path . "/" . $file_name;
        } else {
            $file_url = $base_path . "/" . $file_path . "/" . $file_name;
        }

        // echo $file_url;

        // Check if file exists
        if (file_exists($file_url)) {
            $file_size = filesize($file_url); // Get size in bytes

            return $this->convertBytesToKB($file_size);
        }

        // Default if no file or zero size
        return 0;
    }


    public function filedownload($file_name, $download_path = "")
    {

        $file_url           = $this->_CI->customlib->getFolderPath() . $download_path . "/" . $file_name;
        $download_file_name = substr($file_name, (strpos($file_name, '!') + 1));
        $this->_CI->load->helper('download');
        $data = file_get_contents($file_url);
        force_download($download_file_name, $data);
    }

    public function fileview($file_name)
    {
        if (!IsNullOrEmptyString($file_name)) {

            $download_file_name = substr($file_name, (strpos($file_name, '!') + 1));
            return $download_file_name;
        }
        return null;
    }

    public function getImageURL($file_name)
    {
        if (!IsNullOrEmptyString($file_name)) {

            $download_file_name = $this->_CI->customlib->getBaseUrl() . $file_name . img_time();
            return $download_file_name;
        }
        return null;
    }

    public function filedelete($file_name, $path = "")
    {
        if (!IsNullOrEmptyString($file_name)) {

            $url = $this->_CI->customlib->getFolderPath() . $path . "/" . $file_name;

            if (file_exists($url)) {

                if (unlink($url)) {
                    return true;
                }
            }
        }

        return false;
    }
}
