<?php

class Paginator {

    private $pdo;
    private $query;
    private $value_bind_func;
    private $total_items;

    private $page;
    private $limit;
    private $stmt;

    // Provide the PDO object with connection to the database
    // Provide the query with placeholders
    // Provide the callback function which accepts a PDOStatement and binds values to it
    public function __construct($pdo, $query, $value_bind_func = null) {
        $this->pdo = $pdo;
        $this->query  = $query;
        $this->value_bind_func = $value_bind_func;

        $count_query = "SELECT COUNT(*) FROM (". $query . ") paginator_count_table";
        $count_stmt = $this->pdo->prepare($count_query);
        if(!empty($this->value_bind_func)) {
            call_user_func($this->value_bind_func,$count_stmt);
        }
        $count_stmt->execute();
        $this->total_items = $count_stmt->fetch(PDO::FETCH_ASSOC)['COUNT(*)'];
    }

    // Use prepared statement to get data for this page
    // Defaults to page 1 and no limit
    // Return 1 on success, 0 on failure
    public function updatePage($page = 1, $limit = null) {
        // If no limit is provided then we assume there is no limit (equiv. limit=this->total_items)
        $limit = (empty($limit)) ? $this->total_items : $limit;
        // Store variables
        $this->page = (int)$page;
        $this->limit = (int)$limit;
        // Calculate offset
        $offset = ($page - 1) * $limit;
        // Add limits to query
        $page_query = $this->query . " LIMIT :paginator_limit OFFSET :paginator_offset";
        // Create prepared statement
        $this->stmt = $this->pdo->prepare($page_query);
        // Call user function to bind other parameters if it exists
        if(!empty($this->value_bind_func)) {
            call_user_func($this->value_bind_func,$this->stmt);
        }
        // Bind paginator parameters
        $this->stmt->bindValue(':paginator_limit',$this->limit, PDO::PARAM_INT);
        $this->stmt->bindValue(':paginator_offset',$offset, PDO::PARAM_INT);
        // Execute query
        return $this->stmt->execute();
    }

    // Get next row from the data obtained by updatePage()
    public function fetchNextRow($fetch_style = PDO::FETCH_ASSOC) {
        return $this->stmt->fetch($fetch_style);
    }

    // Returns $num_links pagination links as squential 'li' elements
    // Each element's content is the page number to which it $num_links
    // Each element's class is "$li_class"
    // The current page recieves the class "$li_class $current_page_class"
    public function getPagination($num_links = 5, $li_class = "", $current_page_class = "") {
        $num_links = (int) $num_links;
        // Calculate how many links should go before and how many after the current page
        // If using an even number, more links will go after than before
        $links_before = floor(($num_links - 1)/2);
        $links_after  = floor($num_links / 2);
        $total_pages = ceil($this->total_items / $this->limit);
        // Try and go down by links before, else go to link 1 and add to links $links_after
        if ($this->page - $links_before < 1) {
            $links_before = $this->page - 1;
            $links_after = $num_links - $this->page;
        }
        // Try and go up by links after, else go to link (total_items) and add to links after
        if ($this->page + $links_after > $total_pages) {
            $links_after = $total_pages - $this->page;
            $links_before = $num_links - $links_after - 1;
        }
        // Note if the total_pages is small we may have too many links before
        // Calculate first and last link, making sure they are within range
        $first_link = ($this->page > $links_before) ? ($this->page - $links_before) : (1);
        $last_link = ($this->page + $links_after <= $total_pages) ? ($this->page + $links_after) : ($total_pages);
        $output_str = "";

        $getCopy = $_GET;
        for ($i = $first_link; $i <= $last_link; $i++) {
            $getCopy['page'] = $i;
            $getCopy['limit'] = $this->limit;
            $output_str .= "<li class='$li_class";
            if ($i == $this->page) {
                $output_str.=" $current_page_class";
            }
            $output_str .= "'><a href=?" . http_build_query($getCopy) . ">$i</a></li>";
        }
        return $output_str;
    }

    // Figures out which item is at the top of current page
    // Return query for page which displays this item under the new limit
    public function getNewLimitQuery($newLimit) {
        $topItem = $this->limit * ($this->page -1) + 1;
        $newPage = ceil($topItem / $newLimit);
        $getCopy = $_GET;
        $getCopy['page'] = $newPage;
        $getCopy['limit'] = $newLimit;
        return http_build_query($getCopy);
    }

    public function getPrevPageQuery() {
        // Take one from the page unless we're already at the start
        $getCopy = $_GET;
        if($this->page == 1) {
            $getCopy['page'] = 1;
        } else {
            $getCopy['page'] = $this->page - 1;
        }
        return http_build_query($getCopy);
    }

    public function getNextPageQuery() {
        // Add one to the page unless we're already at the end
        $getCopy = $_GET;
        if($this->page == ceil($this->total_items / $this->limit)) {
            $getCopy['page'] = $this->page;
        } else {
            $getCopy['page'] = $this->page + 1;
        }
        return http_build_query($getCopy);
    }

    public function getFirstPageQuery() {
        $getCopy = $_GET;
        $getCopy['page'] = 1;
        return http_build_query($getCopy);
    }

    public function getLastPageQuery() {
        $getCopy = $_GET;
        $getCopy['page'] = ceil($this->total_items / $this->limit);
        return http_build_query($getCopy);
    }

}
