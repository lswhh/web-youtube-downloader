<?php

    $file = $_SERVER['QUERY_STRING'] ?? null;

    if ($file === null || substr($file, 0, 18) !== 'video_url=https://') {
        die('Cannot find the video_url URL parameter');
    }

    $file = substr($file, 10);
    
    // 추가: format 인자를 가져오기
    parse_str($_SERVER['QUERY_STRING'], $queryParams);
    $format = isset($queryParams['format']) ? $queryParams['format'] : 'mp4';
    $filename = $format === 'mp4' ? 'videoplayback.' . $format : 'audioplayback.' . $format;

    $headers = array_change_key_case(get_headers($file, true));

    $fileSize = (array)$headers['content-length'];

    if (count($fileSize) === 0) {
        die('Cannot fetch the file size');
    }

    if (strpos('404 Not Found', $headers[0]) === false) {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        
        // 수정: filename 변수로 변경
        header('Content-Disposition: attachment; filename=' . $filename);
        
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . $fileSize[count($fileSize)-1]);
        echo "downloading";
        ob_clean();
        flush();
        readfile($file);
        exit;
    } else {
        die($file . " is not found...\n");
    }

    while(true) {
        echo "\n";
        if (connection_status() != 0) {
            die;
        }
    }
