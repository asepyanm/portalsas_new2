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
 * 	Configuration file for the File Manager Connector for PHP.
 * 
 * File Authors:
 * 		Frederico Caldeira Knabben (fredck@fckeditor.net)
 */

global $Config ;

// SECURITY: You must explicitelly enable this "connector". (Set it to "true").
$Config['Enabled'] = true ;

// Path to user files relative to the document root.
$Config['UserFilesPath'] = '_download/' ;
$Config['BaseUrlPath']   = "/";

// Fill the following value it you prefer to specify the absolute path for the
// user files directory. Usefull if you are using a virtual directory, symbolic
// link or alias. Examples: 'C:\\MySite\\UserFiles\\' or '/root/mysite/UserFiles/'.
// Attention: The above 'UserFilesPath' must point to the same directory.
$Config['UserFilesAbsolutePath'] = $_SERVER['DOCUMENT_ROOT'] . '/_download/' ;

$Config['AllowedExtensions']['File']	= array('pdf','txt','doc','xls','zip','rar','tar','tar.gz','xlsx','docx','ppt','pptx') ;
$Config['DeniedExtensions']['File']		= array() ;
$Config['AbsolutePath']['File']	        = $_SERVER['DOCUMENT_ROOT'] . $Config['BaseUrlPath'] . '/bit_upload/' ;
$Config['UrlPath']['File']		        = "http://".$_SERVER['HTTP_HOST']."/".$Config['BaseUrlPath'] . '/bit_upload/' ;

$Config['AllowedExtensions']['Image']	= array('jpg','gif','jpeg','png') ;
$Config['DeniedExtensions']['Image']	= array() ;
$Config['AbsolutePath']['Image']	    = $_SERVER['DOCUMENT_ROOT'] . $Config['BaseUrlPath'] . '/_images/articles/' ;
$Config['UrlPath']['Image']		        = "http://".$_SERVER['HTTP_HOST']."/".$Config['BaseUrlPath'] . '/_images/articles/' ;

$Config['AllowedExtensions']['Flash']	= array('swf','fla') ;
$Config['DeniedExtensions']['Flash']	= array() ;
$Config['AbsolutePath']['Flash']	    = $_SERVER['DOCUMENT_ROOT'] . $Config['BaseUrlPath'] . '/_images/articles/' ;
$Config['UrlPath']['Flash']		        = "http://".$_SERVER['HTTP_HOST']."/".$Config['BaseUrlPath'] . '/_images/articles/' ;

$Config['AllowedExtensions']['Media']	= array('swf','fla','jpg','gif','jpeg','png','avi','mpg','mpeg','mp3') ;
$Config['DeniedExtensions']['Media']	= array() ;
$Config['AbsolutePath']['Media']	    = $_SERVER['DOCUMENT_ROOT'] . $Config['BaseUrlPath'] . '/_images/articles/' ;
$Config['UrlPath']['Media']		        = "http://".$_SERVER['HTTP_HOST']."/".$Config['BaseUrlPath'] . '/_images/articles/' ;
?>
