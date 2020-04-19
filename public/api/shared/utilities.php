<?php

class Utilities
{
	public function getPaging($page, $total_rows, $records_per_page, $page_url)
	{
		// Paging array
		$paging_arr = array();

		// button for first page
		$paging_arr['first'] = $page > 1 ? "{$page_url}page=1" : '';

		// count all the products in the database to calculate the total number of pages
		$total_pages = ceil($total_rows / $records_per_page);

		// range of links to show
		$range = 2;

		// show range of links around current page
		$initial_num = $page - $range;
		$condition_limit_num = ($page + $range) + 1;

		$paging_arr['pages'] = array();
		$page_count = 0;

		for ($x = $initial_num; $x < $condition_limit_num; $x++) {
			// make sure that $x > 0 AND $x <= $total_pages
			if ($x > 0 && $x <= $total_pages) {
				$paging_arr['pages'][$page_count]['page'] = $x;
				$paging_arr['pages'][$page_count]['url'] = "{$page_url}page={$x}";
				$paging_arr['pages'][$page_count]['current_page'] = $x == $page ? 'yes' : 'no';

				$page_count++;
			}
		}

		// Button for last page
		$paging_arr['last'] = $page < $total_pages ? "{$page_url}page={$total_pages}" : '';

		// return paging array
		return $paging_arr;
	}
}
