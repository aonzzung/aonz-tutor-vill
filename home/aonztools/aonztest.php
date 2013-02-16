<?php
require('../wp-blog-header.php');

set_time_limit(0);

$file_handle = fopen("aonz_article.txt", "r");
while (!feof($file_handle)) {
	$line = fgets($file_handle);
	try {
		//echo sprintf($line);
		if(strlen($line)>0)
		{
			list($ignore, $meta_title, $meta_description, $meta_keywords, $content, $link_rewrite) = explode("###", $line);
			
			echo "----------------------------------------<br />";
			echo "# start insert post ".$meta_title."<br />";
			// Create post object
			$my_post = array(
					'post_title'    => "'".$meta_title."'",
					'post_content'  => "'".$content."'",
					'post_status'   => 'publish',
					'post_author'   => 1,
					'post_type'      => 'post',
					'tags_input'     => "'".$meta_keywords."'",
					//'post_name'      => $link_rewrite
					//'post_category' => array(8,39)
			);
			
			// Insert the post into the database
			$post_id=wp_insert_post( $my_post );
			//wp_set_post_terms($post_id,array(4, 6),'',true);
			echo "# end insert post ".$meta_title."<br />";
			echo "----------------------------------------<br />";
			//break;
		}
	}
	catch (Exception $e)
	{
		echo "!!! fail to insert post ".$ignore.$meta_title." ".sprintf($e)."<br />";
		echo "----------------------------------------<br />";
	}
}
fclose($file_handle);


?>