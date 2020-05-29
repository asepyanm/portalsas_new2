<script>
_menuCloseDelay=1;
_menuOpenDelay=1;
_subOffsetTop=2;
_subOffsetLeft=-2;


with(AllImagesStyle=new mm_style()){
bordercolor="#F5BF39";
borderstyle="solid";
borderwidth=0;
fontsize="90%";

fontstyle="normal";
headerbgcolor="#ffffff";
offcolor="#000000"; //font color
oncolor="#A67F17";

padding=5;
pagebgcolor="#";
pagecolor="black";
separatorcolor="#999999";
separatorsize=1;

}

with(bit_styleMenu=new mm_style()){
styleid=1;
fontsize="90%";

//headerbgcolor="#ffffff";
//headercolor="#ffffff";
offbgcolor="#F3F8FB";
offcolor="#000000";
onbgcolor="#C8EDF8";
oncolor="#A67F17";

onsubimage="<? echo $bit_app["third_url"]?>/menu/arrow.gif";
pagebgcolor="#9DB5FF";
pagecolor="#ffffff";
padding=8;

subimage="<? echo $bit_app["third_url"]?>/menu/arrow.gif";
subimagepadding=0;
borderwidth=0;

overfilter="Alpha(opacity=95);Shadow(color='#777777', Direction=135, Strength=1)";
/*
overfilter="Fade(duration=0.2);Alpha(opacity=90);Shadow(color='#777777', Direction=135, Strength=5)";
outfilter="randomdissolve(duration=0.1)";
*/
}

/*============= Menu By Afiat Darmawan ================ */
with(milonic=new menuname("Main Menu")){
style=AllImagesStyle;
top=84;
left=((window.screen.width-910)/2);
alwaysvisible=1;
orientation="horizontal";
<?
	echo 'aI("text=Home;url=inovasi.php");';	
	echo 'aI("text=Transport;url=inovasi.php?cat=4");';	
	echo 'aI("text=IP;url=inovasi.php?cat=3");';	
	echo 'aI("text=Switching;url=inovasi.php?cat=7");';	
	echo 'aI("text=ME;url=inovasi.php?cat=8");';	
?>
}

drawMenus();
</script>
