<?php 
/*
 * FCKeditor - The text editor for internet
 * Copyright (C) 2003-2005 Frederico Caldeira Knabben
 * 
 * Licensed under the terms of the GNU Lesser General Public License:
 * 		http://www.opensource.org/licenses/lgpl-license.php
 * 
 * For further information visit:
 * 		http://www.fckeditor.net/
 * 
 * "Support Open Source software. What about a donation today?"
 * 
 * File Name: config.php
 * 	Configuration file for the PHP File Uploader.
 * 
 * File Authors:
 * 		Frederico Caldeira Knabben (fredck@fckeditor.net)
 */

global $Config ;

// SECURITY: You must explicitelly enable this "uploader". 
$Config['Enabled'] = false ;

// Path to uploaded files relative to the document root.
$Config['UserFilesPath'] = $_SERVER['DOCUMENT_ROOT'] . '/_files/' ;

$Config['AllowedExtensions']['File']	= array('pdf','txt','doc','xls','zip','rar','tar','tar.gz') ;
$Config['DeniedExtensions']['File']		= array() ;
$Config['AbsolutePath']['File']	        = $_SERVER['DOCUMENT_ROOT'] . '/_files/' ;
$Config['UrlPath']['File']		        = 'download/' ;

$Config['AllowedExtensions']['Image']	= array('jpg','gif','jpeg','png') ;
$Config['DeniedExtensions']['Image']	= array() ;
$Config['AbsolutePath']['File']	        = $_SERVER['DOCUMENT_ROOT'] . '/_images/articles/' ;
$Config['UrlPath']['File']		        = 'images/articles/' ;

$Config['AllowedExtensions']['Flash']	= array('swf','fla') ;
$Config['DeniedExtensions']['Flash']	= array() ;
$Config['AbsolutePath']['File']	        = $_SERVER['DOCUMENT_ROOT'] . '/_images/articles/' ;
$Config['UrlPath']['File']		        = 'images/articles/' ;

?>