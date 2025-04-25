<?php

use Toybox\Core\Theme;

require_once(ABSPATH . WPINC . '/version.php');

global $wp_version, $wpdb;

$toyboxDir        = TOYBOX_DIR;
$toyboxCoreDir    = Theme::CORE;
$toyboxVersion    = Theme::VERSION;
$phpVersion       = phpversion();
$mysqlVersion     = $wpdb->db_version();
$wordpressVersion = $wp_version;

// Sizes
function get_database_size()
{
    global $wpdb;

    $query = $wpdb->get_results("
        SELECT SUM(`data_length` + `index_length`) AS \"size\" 
          FROM `information_schema`.`TABLES` 
         WHERE `table_schema` = \"{$wpdb->dbname}\"
      GROUP BY `table_schema`");

    return $query[0]->size;
}

function get_wp_content_size()
{
    $totalBytes = 0;
    $path = realpath(WP_CONTENT_DIR);
    if($path!==false && $path!='' && file_exists($path)){
        foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $object){
            $totalBytes += $object->getSize();
        }
    }
    return $totalBytes;
}

function bytesToHumanReadable($bytes, $decimals = 2) {
    $size = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . $size[$factor];
}

$dbSize        = bytesToHumanReadable(get_database_size());
$wpContentSize = bytesToHumanReadable(get_wp_content_size());

$content = <<<EOL
    <style>
        .toybox-widget-container ul {
            margin-left:  -12px;
            margin-right: -12px;
        }
        
        .toybox-widget-container ul li {
            padding-left:          12px;
            padding-right:         12px;
            padding-top:           6px;
            padding-bottom:        6px;
            display:               grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 2fr);
            gap:                   1rem;
        }
        
        .toybox-widget-container ul li:nth-of-type(odd) {
            background: #f6f7f7;
        }
        
        .toybox-widget-container ul li .label {
            font-weight: 600;
        }
        
        .toybox-widget-container ul li .value {
            word-break: break-word;
        }
    </style>
    
    <div class="toybox-widget-container">
        <ul>
            <li>
                <div class="label">Toybox</div>
                <div class="value">{$toyboxVersion}</div>
            </li>
            
            <li>
                <div class="label">PHP</div>
                <div class="value">{$phpVersion}</div>
            </li>
            
            <li>
                <div class="label">MySQL</div>
                <div class="value">{$mysqlVersion}</div>
            </li>
            
            <li>
                <div class="label">WordPress</div>
                <div class="value">{$wordpressVersion}</div>
            </li>
            
            <li>
                <div class="label">Theme Directory</div>
                <div class="value">{$toyboxDir}</div>
            </li>
            
            <li>
                <div class="label">Core Directory</div>
                <div class="value">{$toyboxCoreDir}</div>
            </li>
            
            <li>
                <div class="label">Document Root</div>
                <div class="value">{$_SERVER['DOCUMENT_ROOT']}</div>
            </li>
            
            <li>
                <div class="label">Database Size</div>
                <div class="value">{$dbSize}</div>
            </li>
            
            <li>
                <div class="label">Content Dir. Size</div>
                <div class="value">{$wpContentSize}</div>
            </li>
        </ul>
    </div>
EOL;


\Toybox\Core\Components\Dashboard::addWidget("toybox_widget", "Toybox", $content);
