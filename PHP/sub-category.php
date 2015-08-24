<?php
	if(is_single()) {
		$category = get_the_category();
		$aa= $category[0]->term_id;
		if(!empty($category[0]->category_parent))
			$aa= $category[0]->category_parent;
	}
	else if(is_archive()) {
		$this_category = get_category($cat); 
		$aa= $this_category->term_id;
		if(!empty($this_category->category_parent)) {
			$this_category = get_category($this_category->category_parent);
			$aa= $this_category->term_id;
		}
	}
	else {
		$aa=0;
	}
	$cat_children = get_term_children( $aa, 'category');
	$link=get_category_link($aa);
	$name=get_cat_name($aa);

	if (!empty($cat_children)&&$aa!=0) {
		echo $name;	
		echo $link;
		wp_list_categories("child_of=".$aa. "&depth=0&hide_empty=0&title_li=");
	}
?>