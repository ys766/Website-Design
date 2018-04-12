<header>
  <h1 id="WebsiteHeader"> TRAVEL BOOK </h1>
  <nav id="menu">
    <ul>
    <?php
    foreach($pages as $page_id => $page_name) {
      $filename = $page_id . ".php";
      if ($current_page == $page_id) {
        echo "<li> <a href=" . $filename . " class='current_page'>" . $page_name . "</a></li>";
      }
      else {
        echo "<li> <a href=" . $filename . ">" . $page_name . "</a></li>";
      }
    }
    ?>
  </ul>
  </nav>
  <?php
  if ($current_user) {
    echo "<h2> Hello " . $current_user["realname"] . "! </h2>";
  }
   ?>
</header>
