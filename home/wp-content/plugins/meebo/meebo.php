<?php
/*
Plugin Name: Meebo Bar
Plugin URI: http://bar.meebo.com
Description: Integrates the Meebo Bar into a Wordpress Site
Version: 1.1
Author: Meebo, Inc.
Author URI: http://meebo.com
*/

add_option("meebo_network_id", "thetutorhut_pe54ta");

add_action('wp_footer', 'insertMeeboHead');
add_action('wp_footer', 'meeboDomReady');

/* From addwmode plugin by Chris Heald */
add_filter("the_content", "add_wmodes", 999999999);
function add_wmode($matches) {
	if(!strstr($matches[2], "wmode")) {
		return $matches[1] . " wmode=\"transparent\" " . $matches[2];
	} else {
		$matches[2] = preg_replace("/wmode=(['\"])[a-z]+\\1/i", "wmode=\"transparent\"", $matches[2]);
		return $matches[1] . " " . $matches[2];
	}
}

function add_wmodes($content) {
	$content = preg_replace_callback("/(<embed) ([^>]*?>)/i", "add_wmode", $content);
	$content = preg_replace("/<param.*?name=(['\"])wmode\\1[^>]*>/", "<param name=\"wmode\" value=\"transparent\">", $content);
	return $content;
}

/*** Meebo Bar functions ***/

function insertMeeboHead() {
	$embed_code =<<<HTML1
<script type="text/javascript">
window.Meebo||function(c){function p(){return["<",i,' onload="var d=',g,";d.getElementsByTagName('head')[0].",
j,"(d.",h,"('script')).",k,"='//cim.meebo.com/cim?iv=",a.v,"&",q,"=",c[q],c[l]?
"&"+l+"="+c[l]:"",c[e]?"&"+e+"="+c[e]:"","'\"></",i,">"].join("")}var f=window,
a=f.Meebo=f.Meebo||function(){(a._=a._||[]).push(arguments)},d=document,i="body",
m=d[i],r;if(!m){r=arguments.callee;return setTimeout(function(){r(c)},100)}a.$=
{0:+new Date};a.T=function(u){a.$[u]=new Date-a.$[0]};a.v=5;var j="appendChild",
h="createElement",k="src",l="lang",q="network",e="domain",n=d[h]("div"),v=n[j](d[h]("m")),
b=d[h]("iframe"),g="document",o,s=function(){a.T("load");a("load")};f.addEventListener?
f.addEventListener("load",s,false):f.attachEvent("onload",s);n.style.display="none";
m.insertBefore(n,m.firstChild).id="meebo";b.frameBorder="0";b.name=b.id="meebo-iframe";
b.allowTransparency="true";v[j](b);try{b.contentWindow[g].open()}catch(w){c[e]=
d[e];o="javascript:var d="+g+".open();d.domain='"+d.domain+"';";b[k]=o+"void(0);"}try{var t=
b.contentWindow[g];t.write(p());t.close()}catch(x){b[k]=o+'d.write("'+p().replace(/"/g,
'\\"')+'");d.close();'}a.T(1)}({network:"thetutorhut_pe54ta"});
</script>

HTML1;
  	echo $embed_code;
}

function meeboDomReady() {
	$button_code = "\n<script type='text/javascript'>".get_option("meebo_custom_buttons")."</script>\n";
	$script_tag = "<script type=\"text/javascript\"> Meebo(\"domReady\") </script>\n";
	echo $button_code.$script_tag;
}

?>
