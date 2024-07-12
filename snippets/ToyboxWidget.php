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
        </ul>
    </div>
EOL;


\Toybox\Core\Components\Dashboard::addWidget("toybox_widget", "Toybox", $content);
