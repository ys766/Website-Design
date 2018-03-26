<nav class="vertical">
  <ul>
    <?php
    foreach(PAGES as $page_id => $page_name) {
      $page_file_name = $page_id . '.php';
      if ($current_page == $page_id) {
        print "<li> <a id=\"current_page\" href=$page_file_name>$page_name</a></li>";
      }
      else {
        print "<li> <a href=$page_file_name>$page_name</a></li>";
      }
    }
    ?>
  </ul>
</nav>