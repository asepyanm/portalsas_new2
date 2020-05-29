<?php



function pagination($zz, $table, $filter, $per_page = 10, $page = 1, $url = '?')
{

    $ora = new clsMysql;
    $ora->logon($bit_app["user_db"], $bit_app["pass_db"]);

    if ($filter == null) {
        $query = "SELECT COUNT(*) as jml FROM $table WHERE publish_flag = 1 ORDER BY id";
    } else {
        $query = "SELECT COUNT(*) as jml FROM $table WHERE publish_flag = 1 AND title LIKE '%$filter%'  ORDER BY id";
    }

    $rsPage = $ora->sql_fetch($query, $bit_app["db"]);
    $total = intval($rsPage->value[1]['jml']);
    $adjacents = "5";

    $page = ($page == 0 ? 1 : $page);
    $start = ($page - 1) * $per_page;

    $prev = $page - 1;
    $next = $page + 1;
    $lastpage = ceil($total / $per_page);
    $lpm1 = $lastpage - 1;

    $pagination = "";

    if ($total < 6) {
        $pagination .= "<ul class='pagination'>";
        $pagination .= "<li class='page-item' aria-current='page'><a class='page-link bg-danger' href='{$url}page=1'><span class='text-light'>1</span></a></li>";
        $pagination .= "</ul>";
    } else {
        if ($lastpage > 1) {
            // $pagination .= "<span>Page $page of $lastpage<span>";
            $pagination .= "<ul class='pagination'>";
            if ($lastpage < 7 + ($adjacents * 2)) {
                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    if ($counter == $page)
                        $pagination .= "<li class='page-item' aria-current='page'><a class='page-link bg-danger'><span class='text-light'>$counter</span></a></li>";
                    else
                        $pagination .= "<li class='page-item'><a class='page-link' href='{$url}page=$counter'>$counter</a></li>";
                }
            } elseif ($lastpage > 5 + ($adjacents * 2)) {
                if ($page < 1 + ($adjacents * 2)) {
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                        if ($counter == $page)
                            $pagination .= "<li class='page-item' aria-current='page'><a class='page-link bg-danger'><span class='text-light'>$counter</span></a></li>";
                        else
                            $pagination .= "<li class='page-item'><a class='page-link' href='{$url}page=$counter'>$counter</a></li>";
                    }
                    $pagination .= "<li class='page-item'><a class='page-link'>...</a></li>";
                    $pagination .= "<li class='page-item'><a class='page-link' href='{$url}page=$lpm1'>$lpm1</a></li>";
                    $pagination .= "<li class='page-item'><a class='page-link' href='{$url}page=$lastpage'>$lastpage</a></li>";
                } elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                    $pagination .= "<li class='page-item'><a class='page-link' href='{$url}page=1'>1</a></li>";
                    $pagination .= "<li class='page-item'><a class='page-link' href='{$url}page=2'>2</a></li>";
                    $pagination .= "<li class='page-item'><a class='page-link'>...</a></li>";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                        if ($counter == $page)
                            $pagination .= "<li class='page-item' aria-current='page'><a class='page-link bg-danger'><span class='text-light'>$counter</span></a></li>";
                        else
                            $pagination .= "<li class='page-item'><a class='page-link' href='{$url}page=$counter'>$counter</a></li>";
                    }
                    $pagination .= "<li class='page-item'><a class='page-link'>...</a></li>";
                    $pagination .= "<li class='page-item'><a class='page-link' href='{$url}page=$lpm1'>$lpm1</a></li>";
                    $pagination .= "<li class='page-item'><a class='page-link' href='{$url}page=$lastpage'>$lastpage</a></li>";
                } else {
                    $pagination .= "<li class='page-item'><a class='page-link' href='{$url}page=1'>1</a></li>";
                    $pagination .= "<li class='page-item'><a class='page-link' href='{$url}page=2'>2</a></li>";
                    $pagination .= "<li class='page-item'><a class='page-link'>...</a></li>";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                        if ($counter == $page)
                            $pagination .= "<li class='page-item' aria-current='page'><a class='page-link bg-danger'><span class='text-light'>$counter</a></span></li>";
                        else
                            $pagination .= "<li class='page-item'><a class='page-link' href='{$url}page=$counter'>$counter</a></li>";
                    }
                }
            }

            if ($page < $counter - 1) {
                $pagination .= "<li class='page-item'><a class='page-link' href='{$url}page=$next'>Next</a></li>";
                $pagination .= "<li class='page-item'><a class='page-link' href='{$url}page=$lastpage'>Last</a></li>";
            } else {
                $pagination .= "<li class='page-item'><a class='page-link'>Next</a></li>";
                $pagination .= "<li class='page-item'><a class='page-link'>Last</a></li>";
            }
            $pagination .= "</ul>\n";
        }
    }


    return $pagination;
}

?>
<script>
    $(document).ready(function() {

        $('.foto').colorbox({
            rel: 'foto',
            transition: "fade",
            width: "75%",
            height: "75%"
        });
    });
</script>