<html>
<head>
 <title>Tree</title>
 <style>
  ul{
  	list-style-type:none; /* setiap list dihilangkan penanda setiap list-nya */
  	padding-left: 12px;
  	margin-left: 12px;
  }
  a:link, a:visited{
  	text-decoration: none;
  	font-size: 12px;
  	color: #006;
  }
  a:hover, a:active{
  	text-decoration: underline;
  }
 </style>
 <script language="javascript" src="mod/jquery.js"></script> 
 <script language="javascript">
  function openTree(id){
  	// ambil semua tag <ul> yang mengandung attribut parent = id dari link yang dipilih
  	var elm = $('ul[@parent='+id+']'); 
  	if(elm != undefined){ // jika element ditemukan
  	  if(elm.css('display') == 'none'){ // jika element dalam keadaan tidak ditampilkan
  	    elm.show(); // tampilkan element 	  	
  	    $('#img'+id).attr('src','content/folderopen.jpg'); // ubah gambar menjadi gambar folder sedang terbuka
  	  }else{
  	  	elm.hide(); // sembunyikan element
  	    $('#img'+id).attr('src','content/folderclose2.jpg'); // ubah gambar menjadi gambar folder sedang tertutup
  	  }
	}
  }
 </script> 
</head>
<body>
<? echo setTitleCari("Dokumen");?>
<?


/* fungsi ini akan terus di looping secara rekursif agar dapat menampilkan menu dengan format tree (pohon)
 * dengan kedalaman jenjang yang tidak terbatas */
function loop($data,$parent){
  global $bit_app;
  if(isset($data[$parent])){ // jika ada anak dari menu maka tampilkan
	/* setiap menu ditampilkan dengan tag <ul> dan apabila nilai $parent bukan 0 maka sembunyikan element 
	 * karena bukan merupakan menu utama melainkan sub menu */
	$str = '<ul parent="'.$parent.'" style="display:'.($parent>0?'none':'').'">'; 
	foreach($data[$parent] as $value){
	  /* variable $child akan bernilai sebuah string apabila ada sub menu dari masing-masing menu utama
	   * dan akan bernilai negatif apabila tidak ada sub menu */
	  $child = loop($data,$value->id); 
	  $str .= '<li>';
	  /* beri tanda sebuah folder dengan warna yang mencolok apabila terdapat sub menu di bawah menu utama 	  	   
	   * dan beri juga event javascript untuk membuka sub menu di dalamnya */
	  $str .= ($child) ? '<a href="javascript:openTree('.$value->id.')"><img src="document/folderclose2.jpg" id="img'.$value->id.'" border="0"></a>' : '<img src="document/folderclose1.jpg">';
	  $str .= '<a href="#">'.$value->folder_name.'</a> [ <a href="#" onClick="document.forms[0].hFolder.value='.$value->id.';document.forms[0].submit()">hapus folder</a> ] </li>';
	  
	 
		$query = mysql_query('SELECT * FROM m_doc_file where folder_id = '.$value->id);
		while($row = mysql_fetch_object($query)){
		   $str .= '&nbsp;&nbsp;&nbsp;&nbsp;<br><img src="document/lastnode.gif"><img src="document/file.gif">';
		   $str .= '<a target="_blank" href="'.$bit_app["folder_url"]."/".$row->file_name.'"> '.$row->file_name.' [ <a href="#" onClick="document.forms[0].hFile.value='.$row->id.';document.forms[0].submit()">hapus file</a> ]</a>';
		}
	  if($child) $str .= $child;
	}
	$str .= '</ul>';
	return $str;
  }else return false;	  
}
mysql_connect('localhost',$bit_app["user_db"],$bit_app["pass_db"]);
mysql_select_db($bit_app["db"]);

if ($_POST["hFolder"]) {
	$query = mysql_query('delete FROM m_doc_folder where id='.$_POST["hFolder"]);
	$data = array();
}

if ($_POST["hFile"]) {
	$query = mysql_query('delete FROM m_doc_file where id='.$_POST["hFile"]);
	$data = array();
}

// tampilkan menu di sortir berdasar id dan parent_id agar menu ditampilkan dengan rapih
$query = mysql_query('SELECT * FROM m_doc_folder ORDER BY id,parent_id');
$data = array();
while($row = mysql_fetch_object($query)){
  $data[$row->parent_id][] = $row; // simpan data dari databae ke dalam variable array 3 dimensi di PHP
}
?>
<?
	$f=new clsForm;
	$f->openForm("frmMain","frmMain","","POST","multipart/form-data");
	
	echo loop($data,0); // lakukan looping menu utama

?>
<? $f->button("add","Buat Folder","button","var win;win=window.open('".$bit_app["path_url"]."/bit_content/content/folderAdd.php','win','top=10,left=200,height=250,width=600,resizable=1,scrollbars=1');win.focus()")?>
<? $f->button("add","Upload File","button","var win;win=window.open('".$bit_app["path_url"]."/bit_content/content/fileAdd.php','win','top=10,left=200,height=250,width=600,resizable=1,scrollbars=1');win.focus()")?>

<input type="hidden" name="hFolder" />
<input type="hidden" name="hFile" />

<? $f->closeForm(); ?>

</body>
</html>
